<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConsultationHistoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 10);
        if ($perPage < 1) {
            $perPage = 10;
        }
        if ($perPage > 100) {
            $perPage = 100;
        }

        $currentUser = $request->user();
        $searchTerm = $request->query('search');

        $sortOrder = $request->query('sort', 'desc');
        if (!in_array($sortOrder, ['asc', 'desc'])) {
            $sortOrder = 'desc';
        }

        // Get the latest transaction_id per patient
        // Optional doctor_id filter — when provided, only show patients seen by this doctor
        $doctorId = $request->query('doctor_id');

        try {
            $latestTxnQuery = DB::table('transactions as t')
                ->select(DB::raw('MAX(t.transaction_id) as transaction_id'))
                ->join('appointments as a', 't.appointment_id', '=', 'a.appointment_id');

            // Apply doctor filter if provided
            if ($doctorId) {
                $latestTxnQuery->where('a.doctor_id', (int) $doctorId);
            }

            // Apply search on the inner query
            if ($searchTerm) {
                $latestTxnQuery->join('users as u', 'a.patient_id', '=', 'u.user_id')
                    ->where(function ($q) use ($searchTerm) {
                        $q->where('u.firstname', 'like', '%' . $searchTerm . '%')
                            ->orWhere('u.middlename', 'like', '%' . $searchTerm . '%')
                            ->orWhere('u.lastname', 'like', '%' . $searchTerm . '%')
                            ->orWhere('u.email', 'like', '%' . $searchTerm . '%');
                    });
            }

            $latestTxnIds = $latestTxnQuery
                ->groupBy('a.patient_id')
                ->pluck('transaction_id');
        } catch (\Exception $e) {
            $query = Transaction::query();
            if ($doctorId) {
                $query->whereHas('appointment', function ($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId);
                });
            }
            $latestTxnIds = $query
                ->orderBy('visit_datetime', 'desc')
                ->orderBy('transaction_id', 'desc')
                ->limit(1000)
                ->pluck('transaction_id');
        }

        if ($latestTxnIds->isEmpty()) {
            return response()->json([
                'data' => [],
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => $perPage,
                'total' => 0,
            ]);
        }

        // Build main query with these IDs
        $query = Transaction::with([
            'appointment.patient',
            'appointment.doctor',
            'appointment.services',
            'prescriptions.doctor',
            'prescriptions.items.medicine',
        ])
        ->whereIn('transaction_id', $latestTxnIds)
        ->orderBy('visit_datetime', $sortOrder)
        ->orderBy('transaction_id', $sortOrder === 'asc' ? 'asc' : 'desc');

        return $query->paginate($perPage);
    }
}
