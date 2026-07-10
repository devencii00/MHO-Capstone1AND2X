<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);
        if ($perPage < 1) {
            $perPage = 15;
        }
        if ($perPage > 100) {
            $perPage = 100;
        }

        $query = Prescription::with(['doctor', 'transaction.appointment.patient', 'items.medicine']);

        $currentUser = $request->user();
        if ($currentUser && $currentUser->role === 'patient') {
            $query->whereHas('transaction.appointment', function ($q) use ($currentUser) {
                $q->whereIn('patient_id', $currentUser->accessiblePatientIds());
            });
        } elseif ($request->filled('patient_id')) {
            $patientId = $request->query('patient_id');
            $query->whereHas('transaction.appointment', function ($q) use ($patientId) {
                $q->where('patient_id', $patientId);
            });
        } elseif ($request->filled('transaction_id')) {
            $query->where('transaction_id', $request->query('transaction_id'));
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->query('doctor_id'));
        }

        return $query->latest('prescribed_datetime')->paginate($perPage);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'transaction_id' => ['required', 'exists:transactions,transaction_id'],
            'doctor_id' => ['required', 'exists:users,user_id'],
            'notes' => ['nullable', 'string'],
            'prescribed_datetime' => ['nullable', 'date'],
        ]);

        $prescription = Prescription::create($data);
        $this->markLinkedAppointmentConsulted($prescription);

        return response()->json($prescription->load(['doctor', 'transaction']), 201);
    }

    public function show(Request $request, Prescription $prescription)
    {
        $currentUser = $request->user();
        if ($currentUser && $currentUser->role === 'patient') {
            $prescription->loadMissing('transaction.appointment');
            $appointment = $prescription->transaction ? $prescription->transaction->appointment : null;
            if (! $appointment || ! $currentUser->canAccessPatientId((int) $appointment->patient_id)) {
                abort(403);
            }
        }

        return $prescription->load(['doctor', 'transaction.appointment.patient', 'items.medicine']);
    }

    public function update(Request $request, Prescription $prescription)
    {
        $currentUser = $request->user();
        if ($currentUser && $currentUser->role === 'patient') {
            abort(403);
        }

        $data = $request->validate([
            'notes' => ['sometimes', 'nullable', 'string'],
            'prescribed_datetime' => ['sometimes', 'nullable', 'date'],
        ]);

        $prescription->update($data);
        $this->markLinkedAppointmentConsulted($prescription);

        return $prescription->refresh()->load(['doctor', 'transaction', 'items']);
    }

    public function destroy(Request $request, Prescription $prescription)
    {
        $currentUser = $request->user();
        if ($currentUser && $currentUser->role === 'patient') {
            abort(403);
        }

        $prescription->delete();

        return response()->json([
            'message' => 'Prescription deleted',
        ]);
    }

    private function markLinkedAppointmentConsulted(Prescription $prescription): void
    {
        $prescription->loadMissing('transaction.appointment');

        $appointment = optional($prescription->transaction)->appointment;
        if (! $appointment) {
            return;
        }

        if ((string) $appointment->status !== 'completed' && (string) $appointment->status !== 'consulted') {
            $appointment->status = 'consulted';
            $appointment->save();
        }

        Queue::query()
            ->where('appointment_id', (int) $appointment->appointment_id)
            ->whereNotIn('status', ['done', 'cancelled', 'no_show', 'skipped'])
            ->update(['status' => 'consulted']);
    }
}
