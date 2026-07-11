<?php

namespace App\Http\Controllers;

use App\Mail\StaffInviteMail;
use App\Models\Appointment;
use App\Models\LogEntry;
use App\Models\MedicalBackground;
use App\Models\Notification;
use App\Models\Prescription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = $request->user();
        if ($currentUser && $currentUser->role === 'patient') {
            abort(403);
        }

        $perPage = (int) $request->query('per_page', 15);
        if ($perPage < 1) {
            $perPage = 15;
        }
        if ($perPage > 100) {
            $perPage = 100;
        }

        $request->validate([
            'search' => ['nullable', 'string'],
            'parents_only' => ['nullable', 'boolean'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:15'],
            'sort' => ['nullable', 'in:asc,desc'],
            'order_by' => ['nullable', 'in:visit_asc,visit_desc,created_asc,created_desc'],
            'age_filter' => ['nullable', 'in:all,0_5,6_12,13_19,20_64,65_up'],
        ]);

        $search = trim((string) $request->query('search', ''));
        $parentsOnly = $request->boolean('parents_only');

        $query = User::query()->where('role', 'patient');

        if ($parentsOnly) {
            $query->where('is_dependent', false);
        }

        if ($search !== '') {
            $contains = '%'.$search.'%';
            $prefix = $search.'%';
            $tokens = preg_split('/\s+/u', $search, -1, PREG_SPLIT_NO_EMPTY) ?: [];

            $query->where(function ($q) use ($search, $contains, $prefix, $tokens) {
                // Prefix match for name columns (can use indexes)
                $q->where('email', 'like', $contains)
                    ->orWhere('firstname', 'like', $prefix)
                    ->orWhere('lastname', 'like', $prefix)
                    ->orWhere('middlename', 'like', $prefix)
                    ->orWhere('contact_number', 'like', $contains)
                    ->orWhere('address', 'like', $contains);

                if (is_numeric($search)) {
                    $q->orWhere('user_id', (int) $search);
                }

                foreach ($tokens as $token) {
                    $tokenPrefix = $token.'%';
                    $q->orWhere(function ($w) use ($tokenPrefix) {
                        $w->where('firstname', 'like', $tokenPrefix)
                            ->orWhere('middlename', 'like', $tokenPrefix)
                            ->orWhere('lastname', 'like', $tokenPrefix);
                    });
                }
            });
        }

        $ageFilter = $request->query('age_filter', 'all');
        if ($ageFilter !== 'all') {
            $ageRanges = [
                '0_5' => [0, 5],
                '6_12' => [6, 12],
                '13_19' => [13, 19],
                '20_64' => [20, 64],
                '65_up' => [65, 999],
            ];
            if (isset($ageRanges[$ageFilter])) {
                [$minAge, $maxAge] = $ageRanges[$ageFilter];
                if ($maxAge >= 999) {
                    $query->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) >= ?', [$minAge]);
                } else {
                    $query->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN ? AND ?', [$minAge, $maxAge]);
                }
            }
        }

        $includeCounts = $request->boolean('include_counts');

        $orderBy = $request->query('order_by', 'created_desc');
        switch ($orderBy) {
            case 'visit_asc':
                $query->orderByRaw('(SELECT MAX(appointment_datetime) FROM appointments WHERE patient_id = users.user_id) ASC');
                break;
            case 'visit_desc':
                $query->orderByRaw('(SELECT MAX(appointment_datetime) FROM appointments WHERE patient_id = users.user_id) DESC');
                break;
            case 'created_asc':
                $query->orderBy('user_id', 'asc');
                break;
            case 'created_desc':
            default:
                $query->orderBy('user_id', 'desc');
                break;
        }

        $result = $query->paginate($perPage);

        if ($includeCounts) {
            // Build the same query without pagination to compute age distribution
            $countsQuery = User::query()->where('role', 'patient');

            if ($parentsOnly) {
                $countsQuery->where('is_dependent', false);
            }

            if ($search !== '') {
                $contains = '%'.$search.'%';
                $prefix = $search.'%';
                $tokens = preg_split('/\s+/u', $search, -1, PREG_SPLIT_NO_EMPTY) ?: [];

                $countsQuery->where(function ($q) use ($search, $contains, $prefix, $tokens) {
                    $q->where('email', 'like', $contains)
                        ->orWhere('firstname', 'like', $prefix)
                        ->orWhere('lastname', 'like', $prefix)
                        ->orWhere('middlename', 'like', $prefix)
                        ->orWhere('contact_number', 'like', $contains)
                        ->orWhere('address', 'like', $contains);

                    if (is_numeric($search)) {
                        $q->orWhere('user_id', (int) $search);
                    }

                    foreach ($tokens as $token) {
                        $tokenPrefix = $token.'%';
                        $q->orWhere(function ($w) use ($tokenPrefix) {
                            $w->where('firstname', 'like', $tokenPrefix)
                                ->orWhere('middlename', 'like', $tokenPrefix)
                                ->orWhere('lastname', 'like', $tokenPrefix);
                        });
                    }
                });
            }

            $allPatients = $countsQuery->select('user_id', 'birthdate')->get();

            $ageCounts = [
                'all' => 0,
                '0_5' => 0,
                '6_12' => 0,
                '13_19' => 0,
                '20_64' => 0,
                '65_up' => 0,
            ];

            foreach ($allPatients as $p) {
                $ageCounts['all']++;
                if ($p->birthdate === null) continue;
                $age = (int) $p->birthdate->diffInYears(now());
                if ($age >= 0 && $age <= 5) $ageCounts['0_5']++;
                elseif ($age >= 6 && $age <= 12) $ageCounts['6_12']++;
                elseif ($age >= 13 && $age <= 19) $ageCounts['13_19']++;
                elseif ($age >= 20 && $age <= 64) $ageCounts['20_64']++;
                elseif ($age >= 65) $ageCounts['65_up']++;
            }

            return response()->json(array_merge(
                $result->toArray(),
                ['age_counts' => $ageCounts]
            ));
        }

        return $result;
    }

    public function store(Request $request)
    {
        $currentUser = $request->user();
        if ($currentUser && $currentUser->role === 'patient') {
            abort(403);
        }

        $data = $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['nullable', 'string', 'min:8'],
            'firstname' => ['nullable', 'string', 'regex:/^[\p{L}\p{M}][\p{L}\p{M}\s\.\'\-\x{00B7}]*$/u'],
            'lastname' => ['nullable', 'string', 'regex:/^[\p{L}\p{M}][\p{L}\p{M}\s\.\'\-\x{00B7}]*$/u'],
            'middlename' => ['nullable', 'string', 'regex:/^[\p{L}\p{M}][\p{L}\p{M}\s\.\'\-\x{00B7}]*$/u'],
            'birthdate' => ['required', 'date'],
            'sex' => ['nullable', 'string'],
            'civil_status' => ['nullable', 'string'],
            'nationality' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'contact_number' => ['nullable', 'string', 'regex:/^\+639\d{9}$/'],
            'emergency_contact' => ['nullable', 'string'],
            'emergency_contact_number' => ['nullable', 'string'],
            'occupation' => ['nullable', 'string'],
            'philhealth_number' => ['nullable', 'string'],
        ]);

        foreach (['firstname', 'middlename', 'lastname'] as $key) {
            if (array_key_exists($key, $data) && $data[$key] !== null) {
                $normalized = preg_replace('/\s+/u', ' ', trim((string) $data[$key]));
                $normalized = preg_replace("/\\s*([\\.'\\-\\x{00B7}])\\s*/u", '$1', $normalized);
                $data[$key] = $normalized === '' ? null : $normalized;
            }
        }

        $plainPassword = isset($data['password']) ? (string) $data['password'] : '';
        $passwordWasProvided = $plainPassword !== '';
        if (! $passwordWasProvided) {
            $plainPassword = Str::random(12);
        }

        $user = DB::transaction(function () use ($data, $plainPassword) {
            $user = User::create([
                'email' => $data['email'],
                'password_hash' => Hash::make($plainPassword),
                'role' => 'patient',
                'status' => 'active',
                'firstname' => $data['firstname'] ?? null,
                'lastname' => $data['lastname'] ?? null,
                'middlename' => $data['middlename'] ?? null,
                'birthdate' => $data['birthdate'] ?? null,
                'sex' => $data['sex'] ?? null,
                'address' => $data['address'] ?? null,
                'contact_number' => $data['contact_number'] ?? null,
                'is_first_login' => true,
                'must_change_credentials' => true,
                'account_activated' => true,
            ]);

            Mail::to($user->email)->queue(new StaffInviteMail($user, $plainPassword));

            return $user;
        });

        // Notify receptionists and admins about new patient registration
        try {
            $patientName = trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? ''));
            if (!$patientName) $patientName = $user->email;
            Notification::notifyReceptionists(
                'A new patient has been registered: ' . $patientName,
                'system',
                'New Patient Registered',
                $user->user_id,
                'users'
            );
            Notification::notifyAdmins(
                'A new patient has been registered: ' . $patientName,
                'system',
                'New Patient Registered',
                $user->user_id,
                'users'
            );
        } catch (\Throwable $e) {
            // Silently fail if notification fails
        }

        return response()->json([
            'user' => $user->refresh(),
            'credentials_emailed' => true,
            'generated_password' => ! $passwordWasProvided,
        ], 201);
    }

    public function show(Request $request, User $patient)
    {
        $currentUser = $request->user();
        if ($currentUser && $currentUser->role === 'patient') {
            abort(403);
        }

        if ($patient->role !== 'patient') {
            abort(404);
        }

        return $patient;
    }

    public function update(Request $request, User $patient)
    {
        $currentUser = $request->user();
        if ($currentUser && $currentUser->role === 'patient') {
            abort(403);
        }

        if ($patient->role !== 'patient') {
            abort(404);
        }

        return app(UserController::class)->update($request, $patient);
    }

    public function destroy(Request $request, User $patient)
    {
        $currentUser = $request->user();
        if ($currentUser && $currentUser->role === 'patient') {
            abort(403);
        }

        if ($patient->role !== 'patient') {
            abort(404);
        }

        return app(UserController::class)->destroy($patient);
    }

    public function dependents(Request $request)
    {
        $currentUser = $request->user();

        if (! $currentUser || $currentUser->role === 'patient') {
            abort(403);
        }

        $data = $request->validate([
            'parent_user_id' => ['required', 'exists:users,user_id'],
        ]);

        $parent = User::query()->findOrFail((int) $data['parent_user_id']);
        if ($parent->role !== 'patient' || $parent->is_dependent) {
            return [];
        }

        return $parent->children()->get();
    }

    public function storeDependent(Request $request)
    {
        $currentUser = $request->user();

        if (! $currentUser || $currentUser->role === 'patient') {
            abort(403);
        }

        $data = $request->validate([
            'parent_user_id' => ['required', 'exists:users,user_id'],
            'firstname' => ['nullable', 'string', 'regex:/^[\p{L}\p{M}][\p{L}\p{M}\s\.\'\-\x{00B7}]*$/u'],
            'lastname' => ['nullable', 'string', 'regex:/^[\p{L}\p{M}][\p{L}\p{M}\s\.\'\-\x{00B7}]*$/u'],
            'middlename' => ['nullable', 'string', 'regex:/^[\p{L}\p{M}][\p{L}\p{M}\s\.\'\-\x{00B7}]*$/u'],
            'birthdate' => ['required', 'date'],
            'sex' => ['nullable', 'string'],
            'civil_status' => ['nullable', 'string'],
            'nationality' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'contact_number' => ['nullable', 'string', 'regex:/^\+639\d{9}$/'],
            'emergency_contact' => ['nullable', 'string'],
            'emergency_contact_number' => ['nullable', 'string'],
            'occupation' => ['nullable', 'string'],
            'philhealth_number' => ['nullable', 'string'],
            'relationship' => ['required', 'in:mother,father,guardian'],
            'email' => ['nullable', 'email'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        foreach (['firstname', 'middlename', 'lastname'] as $key) {
            if (array_key_exists($key, $data) && $data[$key] !== null) {
                $normalized = preg_replace('/\s+/u', ' ', trim((string) $data[$key]));
                $normalized = preg_replace("/\\s*([\\.'\\-\\x{00B7}])\\s*/u", '$1', $normalized);
                $data[$key] = $normalized === '' ? null : $normalized;
            }
        }

        $parent = User::query()->findOrFail((int) $data['parent_user_id']);
        if ($parent->role !== 'patient' || $parent->is_dependent) {
            return response()->json([
                'message' => 'Parent must be a non-dependent patient.',
            ], 422);
        }

        $birthdate = Carbon::parse($data['birthdate']);
        $age = $birthdate->diffInYears(now());

        $requestedEmail = isset($data['email']) ? trim((string) $data['email']) : '';
        if ($requestedEmail === '') {
            $requestedEmail = null;
        }

        $plainPassword = isset($data['password']) ? (string) $data['password'] : '';
        $passwordProvided = $plainPassword !== '';

        if ($requestedEmail !== null) {
            $request->validate([
                'email' => ['email', 'unique:users,email'],
            ]);
        }

        $shouldAutoCredentials = $age < 5;
        $requiresEmailActivation = $age >= 5 && $requestedEmail === null;

        if (! $requiresEmailActivation && ! $passwordProvided) {
            $plainPassword = Str::random(12);
        }

        if (! isset($data['address']) || trim((string) $data['address']) === '') {
            $data['address'] = $parent->address;
        }

        $user = User::create([
            'parent_user_id' => $parent->user_id,
            'email' => $requiresEmailActivation ? null : $requestedEmail,
            'password_hash' => $requiresEmailActivation ? null : Hash::make($plainPassword),
            'role' => 'patient',
            'status' => $requiresEmailActivation ? 'inactive' : 'active',
            'firstname' => $data['firstname'] ?? null,
            'lastname' => $data['lastname'] ?? null,
            'middlename' => $data['middlename'] ?? null,
            'birthdate' => $data['birthdate'] ?? null,
            'sex' => $data['sex'] ?? null,
            'address' => $data['address'] ?? null,
            'contact_number' => $data['contact_number'] ?? null,
            'is_dependent' => true,
            'account_activated' => ! $requiresEmailActivation,
            'relationship' => $data['relationship'] ?? null,
            'is_first_login' => true,
            'must_change_credentials' => ! $requiresEmailActivation,
        ]);

        if ($requiresEmailActivation) {
            return response()->json([
                'dependent' => $user->refresh(),
                'activation' => [
                    'requires_email' => true,
                    'prompt' => 'Add email to activate account',
                ],
            ], 201);
        }

        if ($user->email === null) {
            $generatedEmail = 'dependent'.$user->user_id.'@temp.com';
            if (User::where('email', $generatedEmail)->exists()) {
                $generatedEmail = 'dependent'.$user->user_id.'-'.Str::lower(Str::random(4)).'@temp.com';
            }
            $user->update(['email' => $generatedEmail]);
        }

        $payload = [
            'dependent' => $user->refresh(),
            'activation' => [
                'requires_email' => false,
                'prompt' => null,
            ],
        ];

        $payload['credentials'] = [
            'email' => $user->email,
            'password' => $plainPassword,
            'generated' => ! $passwordProvided || $requestedEmail === null,
        ];

        return response()->json($payload, 201);
    }

    public function activateDependent(Request $request, User $dependent)
    {
        $currentUser = $request->user();

        if (! $currentUser || $currentUser->role === 'patient') {
            abort(403);
        }

        if (! $dependent->is_dependent) {
            return response()->json([
                'message' => 'User is not a dependent.',
            ], 422);
        }

        $data = $request->validate([
            'email' => ['required', 'email', "unique:users,email,{$dependent->user_id},user_id"],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $wasActivated = (bool) $dependent->account_activated;

        $dependent->update([
            'email' => $data['email'],
            'password_hash' => Hash::make($data['password']),
            'account_activated' => true,
            'status' => 'active',
            'is_first_login' => true,
            'must_change_credentials' => true,
        ]);

        if (! $wasActivated && (bool) $dependent->account_activated) {
            Notification::notifyAdmins('[Account Activated] A user activated their account.');
        }

        return $dependent->refresh();
    }

    public function vitals(Request $request)
    {
        $currentUser = $request->user();
        $isPatient = $currentUser && $currentUser->role === 'patient';

        $data = $request->validate([
            'patient_id' => [$isPatient ? 'sometimes' : 'required', 'integer', 'exists:users,user_id'],
            'appointment_id' => ['nullable', 'integer', 'exists:appointments,appointment_id'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:15'],
        ]);

        $perPage = (int) ($data['per_page'] ?? 15);
        if ($perPage < 1) {
            $perPage = 50;
        }
        if ($perPage > 100) {
            $perPage = 100;
        }

        if ($isPatient) {
            $patientId = (int) ($currentUser->user_id ?? 0);
            if (array_key_exists('patient_id', $data)) {
                $candidate = (int) $data['patient_id'];
                if (! $currentUser->canAccessPatientId($candidate)) {
                    abort(403);
                }
                $patientId = $candidate;
            }
        } else {
            $patientId = (int) $data['patient_id'];
        }

        LogEntry::write(
            $currentUser ? (int) $currentUser->user_id : null,
            'access_patient_vitals',
            'patients',
            $patientId,
            [],
            120
        );

        $query = DB::table('vitals')
            ->where('vitals.patient_id', $patientId)
            ->leftJoin('appointments', 'vitals.appointment_id', '=', 'appointments.appointment_id')
            ->leftJoin('users as doctors', 'appointments.doctor_id', '=', 'doctors.user_id')
            ->select([
                'vitals.*',
                'appointments.appointment_datetime',
                'appointments.doctor_id',
                'doctors.firstname as doctor_firstname',
                'doctors.middlename as doctor_middlename',
                'doctors.lastname as doctor_lastname',
            ]);

        if (! empty($data['appointment_id'])) {
            $query->where('vitals.appointment_id', (int) $data['appointment_id']);
        }

        return $query
            ->orderByDesc('vitals.recorded_at')
            ->orderByDesc('vitals.vital_id')
            ->paginate($perPage);
    }

    public function storeVital(Request $request)
    {
        $currentUser = $request->user();
        if (! $currentUser || $currentUser->role === 'patient') {
            abort(403);
        }

        $data = $request->validate([
            'appointment_id' => ['required', 'integer', 'exists:appointments,appointment_id'],
            'height_cm' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'weight_kg' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'blood_pressure' => ['nullable', 'string', 'max:20'],
            'temperature' => ['nullable', 'numeric', 'min:0', 'max:99.9'],
            'pulse_rate' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        $vitalValues = [
            'height_cm' => array_key_exists('height_cm', $data) ? $data['height_cm'] : null,
            'weight_kg' => array_key_exists('weight_kg', $data) ? $data['weight_kg'] : null,
            'blood_pressure' => isset($data['blood_pressure']) ? trim((string) $data['blood_pressure']) : null,
            'temperature' => array_key_exists('temperature', $data) ? $data['temperature'] : null,
            'pulse_rate' => array_key_exists('pulse_rate', $data) ? $data['pulse_rate'] : null,
        ];

        if ($vitalValues['blood_pressure'] === '') {
            $vitalValues['blood_pressure'] = null;
        }

        $hasAnyValue = false;
        foreach ($vitalValues as $value) {
            if ($value !== null && $value !== '') {
                $hasAnyValue = true;
                break;
            }
        }

        if (! $hasAnyValue) {
            return response()->json([
                'message' => 'Provide at least one vital sign or close the modal to skip.',
            ], 422);
        }

        $appointment = DB::table('appointments')
            ->select('appointment_id', 'patient_id')
            ->where('appointment_id', (int) $data['appointment_id'])
            ->first();

        if (! $appointment) {
            return response()->json([
                'message' => 'Appointment not found.',
            ], 404);
        }

        $payload = array_merge($vitalValues, [
            'patient_id' => (int) $appointment->patient_id,
            'appointment_id' => (int) $appointment->appointment_id,
            'recorded_at' => now(),
        ]);

        $existing = DB::table('vitals')
            ->where('appointment_id', (int) $appointment->appointment_id)
            ->orderByDesc('vital_id')
            ->first();

        if ($existing) {
            DB::table('vitals')
                ->where('vital_id', (int) $existing->vital_id)
                ->update($payload);

            $vitalId = (int) $existing->vital_id;
            $action = 'patient_vitals_updated';
        } else {
            $vitalId = (int) DB::table('vitals')->insertGetId($payload, 'vital_id');
            $action = 'patient_vitals_created';
        }

        LogEntry::write(
            (int) $currentUser->user_id,
            $action,
            'vitals',
            $vitalId,
            [
                'patient_id' => (int) $appointment->patient_id,
                'appointment_id' => (int) $appointment->appointment_id,
            ]
        );

        $vital = DB::table('vitals')
            ->leftJoin('appointments', 'vitals.appointment_id', '=', 'appointments.appointment_id')
            ->leftJoin('users as doctors', 'appointments.doctor_id', '=', 'doctors.user_id')
            ->where('vitals.vital_id', $vitalId)
            ->select([
                'vitals.*',
                'appointments.appointment_datetime',
                'appointments.doctor_id',
                'doctors.firstname as doctor_firstname',
                'doctors.middlename as doctor_middlename',
                'doctors.lastname as doctor_lastname',
            ])
            ->first();

        return response()->json($vital, $existing ? 200 : 201);
    }

    public function printPatientReport(Request $request)
    {
        $currentUser = $request->user();
        if (! $currentUser || ! in_array(strtolower((string) $currentUser->role), ['admin', 'doctor'])) {
            abort(403);
        }

        $data = $request->validate([
            'patient_id' => ['required', 'integer', 'exists:users,user_id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
        ]);

        $patient = User::query()->findOrFail((int) $data['patient_id']);
        if ($patient->role !== 'patient') {
            abort(404, 'User is not a patient.');
        }

        $start = ! empty($data['start_date'])
            ? Carbon::parse($data['start_date'])->startOfDay()
            : now()->startOfMonth();
        $end = ! empty($data['end_date'])
            ? Carbon::parse($data['end_date'])->endOfDay()
            : now()->endOfDay();

        if ($start->gt($end)) {
            [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
        }

        // Medical background
        $medicalBackgrounds = MedicalBackground::query()
            ->where('patient_id', $patient->user_id)
            ->orderBy('category')
            ->orderByDesc('created_at')
            ->get();

        // Appointments within date range
        $appointments = Appointment::query()
            ->with(['doctor', 'services'])
            ->where('patient_id', $patient->user_id)
            ->whereBetween('appointment_datetime', [$start, $end])
            ->orderByDesc('appointment_datetime')
            ->get();

        // Completed visits (completed appointments with transactions)
        $completedVisits = Transaction::query()
            ->whereHas('appointment', function ($q) use ($patient) {
                $q->where('patient_id', $patient->user_id)->where('status', 'completed');
            })
            ->with(['appointment.doctor', 'appointment.services'])
            ->whereBetween('transaction_datetime', [$start, $end])
            ->orderByDesc('transaction_datetime')
            ->get();

        // Prescriptions within date range
        $prescriptions = Prescription::query()
            ->with(['doctor', 'items'])
            ->whereHas('transaction.appointment', function ($q) use ($patient) {
                $q->where('patient_id', $patient->user_id);
            })
            ->whereBetween('prescribed_datetime', [$start, $end])
            ->orderByDesc('prescribed_datetime')
            ->get();

        // Transactions within date range
        $transactions = Transaction::query()
            ->whereHas('appointment', function ($q) use ($patient) {
                $q->where('patient_id', $patient->user_id);
            })
            ->with(['appointment.doctor', 'appointment.services'])
            ->whereBetween('transaction_datetime', [$start, $end])
            ->orderByDesc('transaction_datetime')
            ->get();

        // All-time stats for patient summary
        $allAppointments = Appointment::query()->where('patient_id', $patient->user_id)->get();
        $totalAppointments = $allAppointments->count();
        $completedCount = $allAppointments->where('status', 'completed')->count();
        $cancelledCount = $allAppointments->where('status', 'cancelled')->count();
        $allMedBg = MedicalBackground::query()->where('patient_id', $patient->user_id)->get();
        $allergiesCount = $allMedBg->whereIn('category', ['allergy_food', 'allergy_drug'])->count();
        $conditionsCount = $allMedBg->where('category', 'condition')->count();
        $allPrescriptions = Prescription::query()
            ->whereHas('transaction.appointment', function ($q) use ($patient) {
                $q->where('patient_id', $patient->user_id);
            })
            ->count();
        $totalPaid = (float) Transaction::query()
            ->whereHas('appointment', function ($q) use ($patient) {
                $q->where('patient_id', $patient->user_id);
            })
            ->where('payment_status', 'paid')
            ->sum('amount');

        $summary = [
            'total_appointments' => $totalAppointments,
            'completed_visits' => $completedCount,
            'cancelled_appointments' => $cancelledCount,
            'allergies_recorded' => $allergiesCount,
            'active_conditions' => $conditionsCount,
            'prescriptions_issued' => $allPrescriptions,
            'total_amount_paid' => round($totalPaid, 2),
        ];

        $reportPeriodLabel = $start->isSameDay($end)
            ? $start->format('F j, Y')
            : $start->format('F j, Y').' - '.$end->format('F j, Y');

        return response()->view('print.patient_reports', [
            'clinicName' => 'OPOL PRIMARY HEALTHCARE CLINIC',
            'embedded' => $request->boolean('embed'),
            'reportPeriodLabel' => $reportPeriodLabel,
            'generatedOn' => now(),
            'generatedBy' => $this->userDisplayName($currentUser, 'Administrator'),
            'patient' => $patient,
            'medicalBackgrounds' => $medicalBackgrounds,
            'appointments' => $appointments,
            'completedVisits' => $completedVisits,
            'prescriptions' => $prescriptions,
            'transactions' => $transactions,
            'summary' => $summary,
        ]);
    }

    private function userDisplayName($user, string $fallback = 'User'): string
    {
        if (! $user) {
            return $fallback;
        }

        $name = trim(implode(' ', array_filter([
            $user->firstname ?? null,
            $user->middlename ?? null,
            $user->lastname ?? null,
        ], function ($value) {
            return trim((string) $value) !== '';
        })));

        if ($name !== '') {
            return $name;
        }

        $email = trim((string) ($user->email ?? ''));
        if ($email !== '') {
            return $email;
        }

        return $fallback;
    }
}
