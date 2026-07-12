<?php

namespace App\Http\Controllers;

use App\Events\QueueUpdated;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Queue;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultationController extends Controller
{
    /**
     * Submit a complete consultation in a single request.
     *
     * Accepts: appointment_id, doctor_id, diagnosis, treatment_notes,
     *          queue_id (optional, for walk-ins),
     *          items[] (prescription items)
     *
     * Returns the created Prescription with all relationships loaded,
     * ready to be displayed in the receipt modal.
     */
    public function submit(Request $request)
    {
        $currentUser = $request->user();
        if ($currentUser && $currentUser->role === 'patient') {
            abort(403);
        }

        $data = $request->validate([
            'appointment_id' => ['required', 'integer', 'exists:appointments,appointment_id'],
            'doctor_id'      => ['required', 'integer', 'exists:users,user_id'],
            'diagnosis'      => ['nullable', 'string'],
            'treatment_notes'=> ['nullable', 'string'],
            'notes'          => ['nullable', 'string'],
            'queue_id'       => ['nullable', 'integer', 'exists:queues,queue_id'],

            // Prescription items (minimal — dosage/frequency/duration/instructions are nullable)
            'items'           => ['nullable', 'array'],
            'items.*.medicine_id'   => ['required_with:items', 'integer', 'exists:medicines,medicine_id'],
            'items.*.dosage'        => ['nullable', 'string'],
            'items.*.frequency'     => ['nullable', 'string'],
            'items.*.duration'      => ['nullable', 'string'],
            'items.*.instructions'  => ['nullable', 'string'],
        ]);

        $appointmentId = (int) $data['appointment_id'];
        $doctorId = (int) $data['doctor_id'];
        $items = $data['items'] ?? [];

        return DB::transaction(function () use ($appointmentId, $doctorId, $items, $data, $currentUser) {

            // 1. Mark appointment as 'consulted'
            $appointment = Appointment::query()->findOrFail($appointmentId);
            $apptStatus = strtolower(trim((string) ($appointment->status ?? '')));
            if (! in_array($apptStatus, ['completed', 'consulted'], true)) {
                $appointment->update(['status' => 'consulted']);
            }

            // 2. If walk-in, update queue status to 'consulted'
            // Try from explicit queue_id first, then fall back to the appointment's queue relation
            $queueId = ! empty($data['queue_id']) ? (int) $data['queue_id'] : null;
            if (! $queueId) {
                $appointment->loadMissing('queue');
                if ($appointment->relationLoaded('queue') && $appointment->queue) {
                    $queueId = (int) $appointment->queue->queue_id;
                }
            }
            if ($queueId) {
                Queue::query()->where('queue_id', $queueId)
                    ->update(['status' => 'consulted']);

                // Broadcast the queue update so the doctor queue view refreshes in realtime
                $updatedQueue = Queue::query()->with(['appointment.patient', 'appointment.doctor', 'appointment.services'])
                    ->where('queue_id', $queueId)
                    ->first();
                if ($updatedQueue) {
                    $doctorId = $updatedQueue->appointment ? (int) $updatedQueue->appointment->doctor_id : null;
                    event(new QueueUpdated($doctorId, $updatedQueue->toArray()));
                }
            }

            // 3. Create transaction with minimal fields (payment is pending)
            $transaction = Transaction::create([
                'appointment_id'  => $appointmentId,
                'payment_status'  => 'pending',
                'payment_mode'    => null,
                'amount'          => 0,
                'discount_amount' => 0,
                'discount_type'   => 'none',
                'diagnosis'       => $data['diagnosis'] ?? null,
                'treatment_notes' => $data['treatment_notes'] ?? null,
                'visit_datetime'  => now(),
            ]);

            // 4. Create prescription
            $prescription = Prescription::create([
                'transaction_id'      => $transaction->transaction_id,
                'doctor_id'           => $doctorId,
                'notes'               => $data['notes'] ?? null,
                'prescribed_datetime' => now(),
            ]);

            // 5. Create prescription items in bulk (single INSERT for all items)
            if (! empty($items)) {
                $now = now();
                $itemRecords = [];
                foreach ($items as $item) {
                    $itemRecords[] = [
                        'prescription_id' => $prescription->prescription_id,
                        'medicine_id'     => (int) $item['medicine_id'],
                        'dosage'          => $item['dosage'] ?? null,
                        'frequency'       => $item['frequency'] ?? null,
                        'duration'        => $item['duration'] ?? null,
                        'instructions'    => $item['instructions'] ?? null,
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ];
                }
                PrescriptionItem::query()->insert($itemRecords);
            }

            // 6. Return the prescription with full relationships loaded
            return response()->json(
                $prescription->fresh()->load([
                    'doctor',
                    'transaction.appointment.patient',
                    'items.medicine',
                ]),
                201
            );
        });
    }
}
