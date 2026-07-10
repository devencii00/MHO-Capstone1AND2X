<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\PatientVerification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Notification::query()->where('user_id', $user->user_id);

        if ($request->boolean('unread_only')) {
            $query->whereNull('read_at');
        }

        if ($request->boolean('exclude_message')) {
            $query->where('type', '!=', 'message');
        }

        $perPage = (int) $request->query('per_page', 15);
        if ($perPage < 1) $perPage = 15;
        if ($perPage > 100) $perPage = 100;

        $notifications = $query->orderByDesc('created_at')->paginate($perPage);

        // Convert to arrays to include navigation data in serialization
        $notifications->getCollection()->transform(function ($notification) use ($user) {
            $data = $notification->toArray();
            $data['navigation'] = $this->getNavigationData($notification, $user);
            return $data;
        });

        return $notifications;
    }

    public function unreadCount(Request $request)
    {
        $userId = $request->user()->user_id;

        $totalUnread = Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();

        $messageUnread = Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->where('type', 'message')
            ->count();

        $notificationUnread = $totalUnread - $messageUnread;

        return response()->json([
            'total' => $totalUnread,
            'messages' => $messageUnread,
            'notifications' => max(0, $notificationUnread),
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $userId = $request->user()->user_id;

        $updated = Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'message' => 'All notifications marked as read.',
            'updated' => $updated,
        ]);
    }

    public function update(Request $request, Notification $notification)
    {
        $this->authorizeNotificationOwner($request, $notification);

        $data = $request->validate([
            'read_at' => ['sometimes', 'nullable', 'date'],
        ]);

        if ($request->has('is_read')) {
            $isRead = $request->boolean('is_read');
            $data['read_at'] = $isRead ? now() : null;
        }

        $notification->update($data);

        $result = $notification->fresh()->toArray();
        $result['navigation'] = $this->getNavigationData($notification->fresh(), $request->user());

        return response()->json($result);
    }

    public function destroy(Request $request, Notification $notification)
    {
        $this->authorizeNotificationOwner($request, $notification);

        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted',
        ]);
    }

    protected function authorizeNotificationOwner(Request $request, Notification $notification): void
    {
        if ($notification->user_id !== $request->user()->user_id) {
            abort(403, 'Unauthorized');
        }
    }

    /**
     * Role-aware section mapping for navigation URLs.
     */
    protected function getSectionForRole(string $role, string $route): ?string
    {
        $map = [
            'receptionist' => [
                'appointments' => 'book-appointment',
                'walk-ins'     => 'walk-ins',
                'queue'        => 'queue-management',
                'transactions' => 'record-payment',
                'verifications'=> 'verification-oversight',
                'patients'     => 'register-patient',
                'messages'     => null, // special - open modal
            ],
            'admin' => [
                'appointments'     => 'appointments',
                'user-management'  => 'user-management',
                'verifications'    => 'verification-oversight',
                'patient-records'  => 'patient-records',
                'patients'         => 'patient-records',
                'transactions'     => 'reports',
                'staff'            => 'doctor-management',
                'reports'          => 'reports',
            ],
            'doctor' => [
                'appointments' => 'my-schedule',
                'queue'        => 'queue',
                'visits'       => 'visits',
                'prescriptions'=> 'prescriptions',
                'patients'     => 'my-patients',
            ],
        ];

        return $map[$role][$route] ?? null;
    }

    /**
     * Generate navigation data for a notification based on user role.
     */
    protected function getNavigationData($notification, User $user): array
    {
        $role = $user->role ?? 'receptionist';

        $navData = [
            'route'        => null,
            'label'        => null,
            'section'      => null,
            'navigate_url' => null,
            'action'       => null,     // 'open-messages-modal' | null
            'params'       => [],
        ];

        $type = $notification->type ?? '';
        $refTable = $notification->reference_table ?? '';
        $refId = $notification->reference_id;

        // Determine abstract route name
        $abstractRoute = null;

        switch ($type) {
            case 'appointment':
                $abstractRoute = 'appointments';
                $navData['label'] = 'View Appointment';
                break;
            case 'queue':
                $abstractRoute = 'queue';
                $navData['label'] = 'View Queue';
                break;
            case 'payment':
                $abstractRoute = 'transactions';
                $navData['label'] = 'View Transaction';
                break;
            case 'medical_record':
                $abstractRoute = 'patient-records';
                $navData['label'] = 'View Medical Record';
                break;
            default:
                if ($refTable === 'patient_verifications') {
                    $abstractRoute = 'verifications';
                    $navData['label'] = 'View Verification';
                } elseif ($refTable === 'users') {
                    $abstractRoute = 'patients';
                    $navData['label'] = 'View Patient';
                } elseif ($refTable === 'transactions' || $refTable === 'payments') {
                    $abstractRoute = 'transactions';
                    $navData['label'] = 'View Transaction';
                }
                break;
        }

        // For walk-in type notifications, route to walk-ins
        if ($notification->message && str_contains(strtolower($notification->message), 'walk-in')) {
            $abstractRoute = 'walk-ins';
            $navData['label'] = 'View Walk-ins';
        }

        // For patient message notifications
        if ($notification->message && str_contains(strtolower($notification->message), 'sent a message')) {
            $abstractRoute = 'messages';
            $navData['label'] = 'Open Messages';
        }

        // Map abstract route to role-specific section
        if ($abstractRoute) {
            $navData['route'] = $abstractRoute;

            if ($abstractRoute === 'messages' && $role === 'receptionist') {
                $navData['action'] = 'open-messages-modal';
                $navData['label'] = 'Open Messages';
            } else {
                $section = $this->getSectionForRole($role, $abstractRoute);
                if ($section) {
                    $navData['section'] = $section;
                    $navData['navigate_url'] = route('dashboard', [
                        'role' => $role,
                        'section' => $section,
                    ], false);
                }
            }
        }

        return $navData;
    }
}
