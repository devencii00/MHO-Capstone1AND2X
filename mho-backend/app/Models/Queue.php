<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Queue extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'queues';

    protected $primaryKey = 'queue_id';

    public const STATUS_WAITING = 'waiting';
    public const STATUS_SERVING = 'serving';
    public const STATUS_CONSULTED = 'consulted';
    public const STATUS_DONE = 'done';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_NO_SHOW = 'no_show';
    public const STATUS_SKIPPED = 'skipped';
    public const STATUS_ON_HOLD = 'on_hold';

    public const ACTIVE_STATUSES = [
        self::STATUS_WAITING,
        self::STATUS_SERVING,
        self::STATUS_SKIPPED,
        self::STATUS_ON_HOLD,
    ];

    public const TERMINAL_STATUSES = [
        self::STATUS_CONSULTED,
        self::STATUS_DONE,
        self::STATUS_CANCELLED,
        self::STATUS_NO_SHOW,
    ];

    public const STATUSES = [
        self::STATUS_WAITING,
        self::STATUS_SERVING,
        self::STATUS_CONSULTED,
        self::STATUS_DONE,
        self::STATUS_CANCELLED,
        self::STATUS_NO_SHOW,
        self::STATUS_SKIPPED,
        self::STATUS_ON_HOLD,
    ];

    protected $fillable = [
        'appointment_id',
        'queue_number',
        'queue_code',
        'queue_datetime',
        'status',
        'priority_level',
        'skip_count',
        'skip_turns_remaining',
    ];

    protected $casts = [
        'queue_datetime' => 'datetime',
        'skip_count' => 'integer',
        'skip_turns_remaining' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $queue) {
            $priorityLevel = self::sanitizePriorityLevel($queue->priority_level);
            if ($priorityLevel === null && $queue->appointment_id) {
                $appointment = Appointment::query()->find((int) $queue->appointment_id, ['appointment_id', 'patient_id', 'priority_level']);
                if ($appointment) {
                    $priorityLevel = self::sanitizePriorityLevel($appointment->priority_level);
                }
            }

            $queue->priority_level = $priorityLevel ?? 5;
            $queue->skip_count = max(0, (int) ($queue->skip_count ?? 0));
            $queue->skip_turns_remaining = max(0, (int) ($queue->skip_turns_remaining ?? 0));

            $queueCode = is_string($queue->queue_code) ? trim($queue->queue_code) : '';
            if ($queueCode === '') {
                $queue->queue_code = self::generateUniqueQueueCode((int) $queue->priority_level);
            }
        });

        static::updating(function (self $queue) {
            if (! $queue->isDirty('priority_level')) {
                if ($queue->isDirty('skip_count')) {
                    $queue->skip_count = max(0, (int) ($queue->skip_count ?? 0));
                }
                if ($queue->isDirty('skip_turns_remaining')) {
                    $queue->skip_turns_remaining = max(0, (int) ($queue->skip_turns_remaining ?? 0));
                }
                return;
            }

            $queue->priority_level = self::sanitizePriorityLevel($queue->priority_level) ?? 5;

            $queueCode = is_string($queue->queue_code) ? trim($queue->queue_code) : '';
            $expectedPrefix = self::prefixForLevel((int) $queue->priority_level);
            $existingPrefix = strtoupper(trim(explode('-', $queueCode, 2)[0] ?? ''));

            if ($queueCode === '' || $existingPrefix !== $expectedPrefix) {
                $queue->queue_code = self::generateUniqueQueueCode((int) $queue->priority_level);
            }
        });
    }

    public static function sanitizePriorityLevel($value): ?int
    {
        if (is_string($value) && $value !== '' && is_numeric($value)) {
            $value = (int) $value;
        }
        if (! is_int($value)) {
            return null;
        }

        if ($value === 1 || $value === 2) {
            return $value;
        }

        return 5;
    }

    public static function prefixForLevel(int $level): string
    {
        $level = self::sanitizePriorityLevel($level) ?? 5;

        return match ($level) {
            1 => 'EMG',
            2 => 'PRI',
            default => 'REG',
        };
    }

    public static function generateUniqueQueueCode(int $priorityLevel): string
    {
        $prefix = self::prefixForLevel($priorityLevel);

        $tries = 0;
        do {
            $digits = str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT);
            $candidate = $prefix.'-'.$digits;
            $exists = DB::table('queues')->where('queue_code', $candidate)->exists();
            $tries++;
        } while ($exists && $tries < 25);

        return $candidate;
    }

    public static function activeStatuses(): array
    {
        return self::ACTIVE_STATUSES;
    }

    public static function terminalStatuses(): array
    {
        return self::TERMINAL_STATUSES;
    }

    public static function statusRank(?string $status): int
    {
        return match (strtolower(trim((string) $status))) {
            self::STATUS_SERVING => 0,
            self::STATUS_WAITING => 1,
            self::STATUS_SKIPPED => 2,
            self::STATUS_ON_HOLD => 3,
            self::STATUS_CONSULTED => 4,
            self::STATUS_DONE => 5,
            self::STATUS_CANCELLED => 6,
            self::STATUS_NO_SHOW => 7,
            default => 8,
        };
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id', 'appointment_id');
    }
}
