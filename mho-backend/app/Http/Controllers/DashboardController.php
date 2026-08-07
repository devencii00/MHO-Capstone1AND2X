<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\DoctorSchedule;
use App\Models\LogEntry;
use App\Models\Notification;
use App\Models\PatientVerification;
use App\Models\Prescription;
use App\Models\Queue;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function show(Request $request, string $role = 'admin')
    {
        $role = strtolower($role);

        $allowed = ['admin', 'doctor', 'receptionist', 'patient', 'laboratory', 'laboratory_personnel'];

        if (! in_array($role, $allowed, true)) {
            abort(404);
        }

        $section = $request->query('section');
        $userUuid = $request->query('user_uuid');
        $userId = $request->query('user_id');

        $currentUser = null;
        $doctorId = null;

        if ($role !== 'admin') {
            $publicUserKey = $userUuid ?: $userId;
            if ($publicUserKey) {
                $currentUser = User::findByPublicIdentifier($publicUserKey);
            }
        }

        if ($role === 'doctor' && $currentUser) {
            $doctorId = $currentUser->user_id;
        }

        $data = [
            'role' => $role,
            'section' => $section,
            'currentUser' => $currentUser,
        ];

        if ($role === 'admin') {
            // Admin dashboards use shell caching: the page render only ships static
            // structure + skeleton loaders. Dynamic data is served by GET /dashboard/data
            // (see data()) and fetched by each section's init function on every shell show.
        } elseif ($role === 'doctor') {
            $today = now()->toDateString();

            $appointmentsToday = Appointment::whereDate('appointment_datetime', $today)
                ->when($doctorId, function ($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId);
                })
                ->count();

            $queueToday = Queue::whereDate('queue_datetime', $today)
                ->when($doctorId, function ($q) use ($doctorId) {
                    $q->whereHas('appointment', function ($sub) use ($doctorId) {
                        $sub->where('doctor_id', $doctorId);
                    });
                })
                ->count();

            $completedToday = Appointment::whereDate('appointment_datetime', $today)
                ->where('status', 'completed')
                ->when($doctorId, function ($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId);
                })
                ->count();

            $pendingPrescriptionsToday = Transaction::query()
                ->whereDate('visit_datetime', $today)
                ->when($doctorId, function ($q) use ($doctorId) {
                    $q->whereHas('appointment', function ($sub) use ($doctorId) {
                        $sub->where('doctor_id', $doctorId);
                    });
                })
                ->whereDoesntHave('prescriptions')
                ->count();

            $unreadNotificationsCount = 0;
            $recentNotifications = collect();
            if ($currentUser) {
                $unreadNotificationsCount = Notification::where('user_id', $currentUser->user_id)
                    ->whereNull('read_at')
                    ->count();
                $recentNotifications = Notification::where('user_id', $currentUser->user_id)
                    ->latest('created_at')
                    ->limit(10)
                    ->get();
            }

            $recentAppointments = Appointment::with(['patient', 'doctor'])
                ->when($doctorId, function ($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId);
                })
                ->latest('appointment_datetime')
                ->limit(50)
                ->get();

            $recentVisits = Transaction::with(['appointment.patient'])
                ->when($doctorId, function ($q) use ($doctorId) {
                    $q->whereHas('prescriptions', function ($sub) use ($doctorId) {
                        $sub->where('doctor_id', $doctorId);
                    });
                })
                ->latest('visit_datetime')
                ->limit(50)
                ->get();

            $recentQueue = Queue::with(['appointment.patient', 'appointment.doctor'])
                ->when($doctorId, function ($q) use ($doctorId) {
                    $q->whereHas('appointment', function ($sub) use ($doctorId) {
                        $sub->where('doctor_id', $doctorId);
                    });
                })
                ->latest('queue_datetime')
                ->limit(50)
                ->get();

            $todayAppointments = Appointment::with(['patient', 'doctor', 'queue', 'transaction', 'services'])
                ->whereDate('appointment_datetime', $today)
                ->when($doctorId, function ($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId);
                })
                ->orderBy('appointment_datetime')
                ->get();

            $todayQueue = Queue::with(['appointment.patient', 'appointment.doctor'])
                ->whereDate('queue_datetime', $today)
                ->when($doctorId, function ($q) use ($doctorId) {
                    $q->whereHas('appointment', function ($sub) use ($doctorId) {
                        $sub->where('doctor_id', $doctorId);
                    });
                })
                ->orderBy('queue_number')
                ->orderBy('queue_datetime')
                ->get();

            $activeQueueCount = $todayQueue->filter(function ($row) {
                return in_array($row->status, ['waiting', 'serving'], true);
            })->count();

            $doctorPatients = collect();

            if ($doctorId) {
                $patientIds = Appointment::where('doctor_id', $doctorId)
                    ->distinct()
                    ->pluck('patient_id');

                $doctorPatients = User::where('role', 'patient')
                    ->whereIn('user_id', $patientIds)
                    ->latest('user_id')
                    ->limit(50)
                    ->get();
            }

            $recentPrescriptions = Prescription::with(['transaction.appointment.patient', 'doctor', 'items'])
                ->when($doctorId, function ($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId);
                })
                ->latest('prescribed_datetime')
                ->limit(50)
                ->get();

            $appointmentsCountQuery = Appointment::query();
            $visitsCountQuery = Transaction::query();
            $prescriptionsCountQuery = Prescription::query();
            $queueCountQuery = Queue::query();

            if ($doctorId) {
                $appointmentsCountQuery->where('doctor_id', $doctorId);
                $visitsCountQuery->whereHas('prescriptions', function ($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId);
                });
                $prescriptionsCountQuery->where('doctor_id', $doctorId);
                $queueCountQuery->whereHas('appointment', function ($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId);
                });
            }

            $activitySummary = [
                'totalAppointments' => $appointmentsCountQuery->count(),
                'totalVisits' => $visitsCountQuery->count(),
                'totalPrescriptions' => $prescriptionsCountQuery->count(),
                'totalQueueEntries' => $queueCountQuery->count(),
            ];

            $data['doctorMetrics'] = [
                'appointmentsToday' => $appointmentsToday,
                'queueToday' => $activeQueueCount,
                'completedToday' => $completedToday,
                'pendingPrescriptionsToday' => $pendingPrescriptionsToday,
                'unreadNotificationsCount' => $unreadNotificationsCount,
            ];

            $data['doctorRecentAppointments'] = $recentAppointments;
            $data['doctorRecentVisits'] = $recentVisits;
            $data['doctorRecentQueue'] = $recentQueue;
            $data['doctorTodayAppointments'] = $todayAppointments;
            $data['doctorTodayQueue'] = $todayQueue;
            $data['doctorRecentNotifications'] = $recentNotifications;
            $data['doctorPatients'] = $doctorPatients;
            $data['doctorRecentPrescriptions'] = $recentPrescriptions;
            $data['doctorActivitySummary'] = $activitySummary;
            $data['currentUser'] = $currentUser;
        } elseif ($role === 'receptionist') {
            $today = now()->toDateString();

            $newRegistrationsToday = User::where('role', 'patient')
                ->whereDate('created_at', $today)
                ->count();

            $appointmentsToday = Appointment::whereDate('appointment_datetime', $today)->count();

            $walkInsToday = Appointment::whereDate('appointment_datetime', $today)
                ->where('appointment_type', 'walk_in')
                ->count();

            $pendingQueueRequests = Appointment::query()
                ->where('status', 'pending')
                ->whereDate('created_at', $today)
                ->where(function ($q) {
                    $q->where('appointment_type', 'scheduled')
                        ->orWhere(function ($inner) {
                            $inner->where('appointment_type', 'walk_in')
                                ->whereNull('created_by');
                        });
                })
                ->count();

            $waitingCount = Queue::whereDate('queue_datetime', $today)
                ->where('status', 'waiting')
                ->count();

            $currentQueueCount = Queue::whereDate('queue_datetime', $today)
                ->whereIn('status', ['waiting', 'serving'])
                ->count();

            $transactionsToday = Transaction::whereDate('transaction_datetime', $today)
                ->where('payment_status', 'paid')
                ->sum('amount');

            $receptionQueue = Queue::with([
                    'appointment.patient',
                    'appointment.doctor',
                    'appointment.services',
                ])
                ->whereDate('queue_datetime', $today)
                ->orderBy('priority_level')
                ->orderBy('queue_number')
                ->get();

            $now = now();
            $dayKey = strtolower($now->format('D'));
            $time = $now->format('H:i:s');

            $todayDoctorSchedules = DoctorSchedule::query()
                ->with(['doctor'])
                ->where('day_of_week', $dayKey)
                ->where('is_available', true)
                ->orderBy('start_time')
                ->get();

            $activeDoctorSchedules = $todayDoctorSchedules
                ->filter(function (DoctorSchedule $schedule) use ($time) {
                    return $schedule->start_time <= $time && $schedule->end_time >= $time;
                })
                ->values();

            $receptionDoctorSlots = $activeDoctorSchedules
                ->groupBy('doctor_id')
                ->map(function ($group) {
                    return $group->sortBy('start_time')->first();
                })
                ->filter()
                ->values();

            $receptionAppointments = Appointment::with(['patient', 'doctor'])
                ->whereDate('appointment_datetime', $today)
                ->orderBy('appointment_datetime')
                ->get();

            $data['receptionMetrics'] = [
                'newRegistrationsToday' => $newRegistrationsToday,
                'appointmentsToday' => $appointmentsToday,
                'walkInsToday' => $walkInsToday,
                'pendingQueueRequests' => $pendingQueueRequests,
                'waitingCount' => $waitingCount,
                'currentQueueCount' => $currentQueueCount,
                'transactionsToday' => $transactionsToday,
            ];

            $data['receptionQueue'] = $receptionQueue;
            $data['receptionAppointments'] = $receptionAppointments;
            $data['receptionDoctorSlots'] = $receptionDoctorSlots;
        } elseif ($role === 'patient') {
            $data['patientDashboard'] = true;
        }

        return view('dashviews.main', $data);
    }

    /**
     * JSON endpoint backing the shell-cache architecture.
     * Returns only the dynamic data a section needs; page shells ship skeletons
     * and fetch here every time they are shown, so cached shells never go stale.
     */
    public function data(Request $request)
    {
        $role = strtolower((string) $request->query('role', 'admin'));
        if (!in_array($role, ['admin', 'doctor', 'receptionist', 'patient', 'laboratory', 'laboratory_personnel'], true)) {
            return response()->json(['ok' => false, 'message' => 'Invalid role.'], 422);
        }

        if ($role === 'admin') {
            return response()->json([
                'ok' => true,
                'data' => $this->adminData((string) $request->query('section', 'overview')),
            ]);
        }

        if ($role === 'receptionist') {
            return response()->json([
                'ok' => true,
                'data' => $this->receptionistData((string) $request->query('section', 'overview')),
            ]);
        }

        if ($role === 'doctor') {
            $doctorId = $request->query('doctor_id') ? (int) $request->query('doctor_id') : null;
            if (!$doctorId) {
                $publicUserKey = $request->query('user_uuid') ?: $request->query('user_id');
                if ($publicUserKey) {
                    $user = User::findByPublicIdentifier($publicUserKey);
                    $doctorId = $user ? (int) $user->user_id : null;
                }
            }

            return response()->json([
                'ok' => true,
                'data' => $this->doctorData((string) $request->query('section', 'overview'), $doctorId),
            ]);
        }

        // Patient payload is added when that role is converted.
        return response()->json(['ok' => true, 'data' => []]);
    }

    /**
     * Section-aware dynamic data for the doctor role.
     */
    private function doctorData(?string $section, ?int $doctorId): array
    {
        $today = now()->toDateString();

        $metrics = function () use ($today, $doctorId) {
            $appointmentsToday = Appointment::whereDate('appointment_datetime', $today)
                ->when($doctorId, function ($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId);
                })
                ->count();

            $todayQueue = Queue::with(['appointment'])
                ->whereDate('queue_datetime', $today)
                ->when($doctorId, function ($q) use ($doctorId) {
                    $q->whereHas('appointment', function ($sub) use ($doctorId) {
                        $sub->where('doctor_id', $doctorId);
                    });
                })
                ->get();

            $activeQueueCount = $todayQueue->filter(function ($row) {
                return in_array($row->status, ['waiting', 'serving'], true);
            })->count();

            $completedToday = Appointment::whereDate('appointment_datetime', $today)
                ->where('status', 'completed')
                ->when($doctorId, function ($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId);
                })
                ->count();

            return [
                'appointmentsToday' => $appointmentsToday,
                'queueToday' => $activeQueueCount,
                'completedToday' => $completedToday,
            ];
        };

        $todayAppointments = function () use ($today, $doctorId) {
            return Appointment::with(['patient', 'doctor', 'queue', 'transaction', 'services'])
                ->whereDate('appointment_datetime', $today)
                ->when($doctorId, function ($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId);
                })
                ->orderBy('appointment_datetime')
                ->get()
                ->map(function ($a) {
                    $patient = optional($a->patient);
                    $queue = optional($a->queue);
                    return [
                        'appointment_id' => $a->appointment_id,
                        'appointment_datetime' => optional($a->appointment_datetime)->format('Y-m-d H:i'),
                        'appointment_type' => $a->appointment_type,
                        'status' => $a->status,
                        'reason_for_visit' => $a->reason_for_visit,
                        'patient' => [
                            'firstname' => $patient->firstname ?? null,
                            'middlename' => $patient->middlename ?? null,
                            'lastname' => $patient->lastname ?? null,
                            'email' => $patient->email ?? null,
                            'contact_no' => $patient->contact_no ?? ($patient->contact_number ?? null),
                            'sex' => $patient->sex ?? null,
                            'birthdate' => optional($patient->birthdate)->format('Y-m-d'),
                            'address' => $patient->address ?? null,
                        ],
                        'queue' => [
                            'status' => $queue->status ?? null,
                            'queue_code' => $queue->queue_code ?? null,
                        ],
                        'services' => collect($a->services ?? [])->map(function ($s) {
                            return [
                                'name' => $s->name ?? null,
                                'service_name' => $s->service_name ?? null,
                                'service_id' => $s->service_id ?? null,
                            ];
                        })->values(),
                    ];
                })
                ->values()
                ->all();
        };

        $queueRows = function (string $statusFilter) use ($today, $doctorId) {
            $queueItems = Queue::with(['appointment.patient'])
                ->whereDate('queue_datetime', $today)
                ->when($doctorId, function ($q) use ($doctorId) {
                    $q->whereHas('appointment', function ($sub) use ($doctorId) {
                        $sub->where('doctor_id', $doctorId);
                    });
                })
                ->get();

            $rows = $statusFilter === 'on_hold'
                ? $queueItems->filter(function ($q) {
                    return strtolower((string) ($q->status ?? '')) === 'on_hold';
                })
                : $queueItems->reject(function ($q) {
                    return strtolower((string) ($q->status ?? '')) === 'on_hold';
                });

            return $rows->sortBy(function ($q) {
                $status = strtolower((string) ($q->status ?? ''));
                $rank = match ($status) {
                    'serving' => 1,
                    'waiting', 'skipped' => 3,
                    'awaiting_payment' => 4,
                    'done' => 5,
                    'on_hold' => 2,
                    default => 6,
                };
                $priority = (int) ($q->priority_level ?? 5);
                $number = (int) ($q->queue_number ?? 999999);
                return str_pad((string) $rank, 6, '0', STR_PAD_LEFT) . '-' . str_pad((string) $priority, 6, '0', STR_PAD_LEFT) . '-' . str_pad((string) $number, 6, '0', STR_PAD_LEFT);
            })->map(function ($q) {
                $patient = optional(optional($q->appointment)->patient);
                $patientParts = array_filter([
                    $patient->firstname ?? null,
                    $patient->middlename ?? null,
                    $patient->lastname ?? null,
                ], function ($v) {
                    return (string) $v !== '';
                });
                $patientName = trim(implode(' ', $patientParts));
                if ($patientName === '') {
                    $patientName = $patient->email ?? ('Patient #' . (optional($q->appointment)->patient_id ?? ''));
                }
                return [
                    'queue_id' => $q->queue_id,
                    'queue_code' => $q->queue_code,
                    'queue_number' => $q->queue_number,
                    'status' => $q->status,
                    'queue_datetime' => optional($q->queue_datetime)->format('Y-m-d H:i'),
                    'patient_name' => $patientName,
                ];
            })->values()->all();
        };

        switch ($section) {
            case 'overview':
            default:
                return [
                    'metrics' => $metrics(),
                    'todayAppointments' => $todayAppointments(),
                    'activeQueue' => $queueRows('active'),
                    'onHoldQueue' => $queueRows('on_hold'),
                ];
        }
    }

    /**
     * Section-aware dynamic data for the receptionist role.
     */
    private function receptionistData(?string $section): array
    {
        $today = now()->toDateString();

        $metrics = function () use ($today) {
            $pendingQueueRequests = Appointment::query()
                ->where('status', 'pending')
                ->whereDate('created_at', $today)
                ->where(function ($q) {
                    $q->where('appointment_type', 'scheduled')
                        ->orWhere(function ($inner) {
                            $inner->where('appointment_type', 'walk_in')
                                ->whereNull('created_by');
                        });
                })
                ->count();

            return [
                'newRegistrationsToday' => User::where('role', 'patient')
                    ->whereDate('created_at', $today)
                    ->count(),
                'appointmentsToday' => Appointment::whereDate('appointment_datetime', $today)->count(),
                'walkInsToday' => Appointment::whereDate('appointment_datetime', $today)
                    ->where('appointment_type', 'walk_in')
                    ->count(),
                'pendingQueueRequests' => $pendingQueueRequests,
                'waitingCount' => Queue::whereDate('queue_datetime', $today)
                    ->where('status', 'waiting')
                    ->count(),
                'currentQueueCount' => Queue::whereDate('queue_datetime', $today)
                    ->whereIn('status', ['waiting', 'serving'])
                    ->count(),
                'transactionsToday' => (float) Transaction::whereDate('transaction_datetime', $today)
                    ->where('payment_status', 'paid')
                    ->sum('amount'),
            ];
        };

        $doctorSlots = function () {
            $now = now();
            $dayKey = strtolower($now->format('D'));
            $time = $now->format('H:i:s');

            $todayDoctorSchedules = DoctorSchedule::query()
                ->with(['doctor'])
                ->where('day_of_week', $dayKey)
                ->where('is_available', true)
                ->orderBy('start_time')
                ->get();

            $activeDoctorSchedules = $todayDoctorSchedules
                ->filter(function (DoctorSchedule $schedule) use ($time) {
                    return $schedule->start_time <= $time && $schedule->end_time >= $time;
                })
                ->values();

            return $activeDoctorSchedules
                ->groupBy('doctor_id')
                ->map(function ($group) {
                    return $group->sortBy('start_time')->first();
                })
                ->filter()
                ->values()
                ->map(function ($slot) {
                    $doctor = optional($slot)->doctor;
                    return [
                        'doctor_id' => (int) ($slot->doctor_id ?? 0),
                        'doctor_name' => (string) (
                            optional(optional($doctor)->personalInformation)->full_name
                            ?? $slot->doctor_name
                            ?? 'Doctor'
                        ),
                        'doctor_designation' => (string) (
                            optional($doctor)->designation
                            ?? $slot->doctor_designation
                            ?? ''
                        ),
                        'slot_start' => optional($slot)->start_time,
                        'slot_end' => optional($slot)->end_time,
                    ];
                })
                ->filter(function ($slot) {
                    return $slot['doctor_id'] > 0;
                })
                ->values()
                ->all();
        };

        switch ($section) {
            case 'overview':
            default:
                return [
                    'metrics' => $metrics(),
                    'doctorSlots' => $doctorSlots(),
                ];
        }
    }

    /**
     * Section-aware dynamic data for the admin role.
     */
    private function adminData(?string $section): array
    {
        $today = now()->toDateString();
        $startOfMonth = now()->startOfMonth()->toDateString();

        $metrics = function () use ($today, $startOfMonth) {
            return [
                'patientCount' => User::where('role', 'patient')->count(),
                'doctorCount' => User::where('role', 'doctor')->count(),
                'pendingVerificationsCount' => PatientVerification::where('status', 'pending')->count(),
                'recentLogsCount' => LogEntry::count(),
                'appointmentsToday' => Appointment::whereDate('appointment_datetime', $today)->count(),
                'revenueToday' => Transaction::whereDate('transaction_datetime', $today)
                    ->where('payment_status', 'paid')
                    ->sum('amount'),
                'revenueThisMonth' => Transaction::whereBetween('transaction_datetime', [$startOfMonth, now()])
                    ->where('payment_status', 'paid')
                    ->sum('amount'),
            ];
        };

        $logs = function (string $kind) {
            $query = LogEntry::with('user');
            if ($kind === 'access') {
                $query->where('action', 'like', 'access_%');
            } else {
                $query->where('action', 'not like', 'access_%');
            }
            return $query->latest('created_at')->limit(60)->get()->map(function ($log) {
                return [
                    'created_at' => optional($log->created_at)->format('Y-m-d H:i'),
                    'user_email' => $log->user ? $log->user->email : null,
                    'action' => $log->action,
                    'table_name' => $log->table_name,
                    'record_id' => $log->record_id,
                ];
            })->values();
        };

        $charts = function () {
            $appointmentsChartStart = now()->subDays(13)->startOfDay();
            $revenueChartStart = now()->subMonths(11)->startOfMonth();

            $appointmentsCounts = Appointment::query()
                ->selectRaw('DATE(appointment_datetime) as day, COUNT(*) as total_count')
                ->whereNotNull('appointment_datetime')
                ->where('appointment_datetime', '>=', $appointmentsChartStart)
                ->groupBy(DB::raw('DATE(appointment_datetime)'))
                ->orderBy('day')
                ->get()
                ->keyBy('day');

            $appointmentLabels = [];
            $appointmentValues = [];
            for ($cursor = $appointmentsChartStart->copy(); $cursor->lte(now()); $cursor->addDay()) {
                $key = $cursor->toDateString();
                $appointmentLabels[] = $key;
                $appointmentValues[] = (int) (($appointmentsCounts[$key]->total_count ?? 0));
            }

            $revenueRows = Transaction::query()
                ->selectRaw("DATE_FORMAT(transaction_datetime, '%Y-%m') as month_key, SUM(amount) as total_amount")
                ->whereNotNull('transaction_datetime')
                ->where('transaction_datetime', '>=', $revenueChartStart)
                ->where('payment_status', 'paid')
                ->groupBy(DB::raw("DATE_FORMAT(transaction_datetime, '%Y-%m')"))
                ->orderBy('month_key')
                ->get()
                ->keyBy('month_key');

            $revenueLabels = [];
            $revenueValues = [];
            for ($cursor = $revenueChartStart->copy(); $cursor->lte(now()); $cursor->addMonth()) {
                $key = $cursor->format('Y-m');
                $revenueLabels[] = $key;
                $revenueValues[] = (float) (($revenueRows[$key]->total_amount ?? 0));
            }

            return [
                'appointmentsPerDay' => ['labels' => $appointmentLabels, 'values' => $appointmentValues],
                'revenuePerMonth' => ['labels' => $revenueLabels, 'values' => $revenueValues],
            ];
        };

        $recentUsers = function () {
            return User::withCount('children')->latest('user_id')->limit(100)->get()->map(function ($u) {
                return [
                    'user_id' => $u->user_id,
                    'email' => $u->email,
                    'firstname' => $u->firstname,
                    'middlename' => $u->middlename,
                    'lastname' => $u->lastname,
                    'contact_number' => $u->contact_number,
                    'role' => $u->role,
                    'status' => $u->status ?? 'active',
                    'created_at' => optional($u->created_at)->format('Y-m-d'),
                    'created_ts' => optional($u->created_at)->timestamp ?? 0,
                    'children_count' => (int) ($u->children_count ?? 0),
                ];
            })->values();
        };

        switch ($section) {
            case 'user-management':
                return ['recentUsers' => $recentUsers()];
            case 'reports':
                return ['metrics' => $metrics()];
            case 'logs':
                return [
                    'recentAuditLogs' => $logs('audit'),
                    'recentAccessLogs' => $logs('access'),
                ];
            case 'overview':
            default:
                return [
                    'metrics' => $metrics(),
                    'charts' => $charts(),
                    'recentAuditLogs' => $logs('audit'),
                ];
        }
    }
}
