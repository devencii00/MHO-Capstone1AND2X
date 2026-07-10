<?php

namespace App\Models;

use App\Events\NewNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'notifications';

    protected $primaryKey = 'notification_id';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'reference_id',
        'reference_table',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the referenced record (polymorphic-like via reference_table).
     */
    public function reference()
    {
        $table = $this->reference_table;
        $id = $this->reference_id;

        if (!$table || !$id) {
            return null;
        }

        $modelMap = [
            'appointments' => Appointment::class,
            'queues' => Queue::class,
            'payments' => Transaction::class,
            'transactions' => Transaction::class,
            'medical_backgrounds' => MedicalBackground::class,
            'users' => User::class,
            'patient_verifications' => PatientVerification::class,
        ];

        $modelClass = $modelMap[$table] ?? null;

        if ($modelClass) {
            return $this->belongsTo($modelClass, 'reference_id', (new $modelClass)->getKeyName());
        }

        return null;
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function markAsRead(): void
    {
        if ($this->read_at === null) {
            $this->update(['read_at' => now()]);
        }
    }

    public static function notifyAdmins(
        string $message,
        string $type = 'system',
        ?string $title = null,
        $referenceId = null,
        ?string $referenceTable = null
    ): void {
        static::notifyRole('admin', $message, $type, $title, $referenceId, $referenceTable);
    }

    public static function notifyReceptionists(
        string $message,
        string $type = 'system',
        ?string $title = null,
        $referenceId = null,
        ?string $referenceTable = null
    ): void {
        static::notifyRole('receptionist', $message, $type, $title, $referenceId, $referenceTable);
    }

    public static function notifyRole(
        string $role,
        string $message,
        string $type = 'system',
        ?string $title = null,
        $referenceId = null,
        ?string $referenceTable = null
    ): void {
        $userIds = User::query()
            ->where('role', $role)
            ->where('status', 'active')
            ->pluck('user_id')
            ->all();

        static::notifyUsers($userIds, $message, $type, $title, $referenceId, $referenceTable);
    }

    public static function notifyUsers(
        array $userIds,
        string $message,
        string $type = 'system',
        ?string $title = null,
        $referenceId = null,
        ?string $referenceTable = null
    ): void {
        $userIds = array_values(array_unique(array_filter(array_map(function ($id) {
            return is_numeric($id) ? (int) $id : null;
        }, $userIds))));

        if ($userIds === []) {
            return;
        }

        $now = now();

        // Generate a default title from the type if not provided
        if ($title === null) {
            $title = match ($type) {
                'appointment' => 'Appointment Update',
                'queue' => 'Queue Update',
                'payment' => 'Payment Update',
                'medical_record' => 'Medical Record Update',
                default => 'System Notification',
            };
        }

        $records = array_map(function (int $userId) use ($message, $type, $title, $referenceId, $referenceTable, $now) {
            return [
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'reference_id' => $referenceId !== null ? (int) $referenceId : null,
                'reference_table' => $referenceTable,
                'read_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }, $userIds);

        foreach ($records as $record) {
            $notification = static::query()->create($record);

            // Broadcast real-time event
            try {
                event(new NewNotification($record['user_id'], $notification));
            } catch (\Throwable $e) {
                // Silently fail if broadcasting fails
            }
        }
    }
}
