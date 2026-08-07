<?php

use Illuminate\Support\Carbon;

if (! function_exists('mho_time')) {
    /**
     * Global 12-hour time formatter (single source of truth for PHP/Blade display).
     * Accepts a Carbon instance or any date string; server timezone is Asia/Manila.
     * Returns e.g. "2:30 PM". Falls back to the raw string if parsing fails.
     */
    function mho_time($value)
    {
        if ($value === null || $value === '') {
            return '-';
        }
        try {
            return Carbon::parse($value)->format('g:i A');
        } catch (\Throwable $e) {
            return (string) $value;
        }
    }
}

if (! function_exists('mho_datetime')) {
    /**
     * Global 12-hour datetime formatter: "YYYY-MM-DD h:mm AM/PM" (e.g. "2025-07-10 2:30 PM").
     */
    function mho_datetime($value)
    {
        if ($value === null || $value === '') {
            return '-';
        }
        try {
            return Carbon::parse($value)->format('Y-m-d g:i A');
        } catch (\Throwable $e) {
            return (string) $value;
        }
    }
}
