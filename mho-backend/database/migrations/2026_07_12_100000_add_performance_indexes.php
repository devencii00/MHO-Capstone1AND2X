<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Index for date-range queries on appointments (receptionist dashboard, calendar views)
        Schema::table('appointments', function (Blueprint $table) {
            $table->index('appointment_datetime', 'idx_appointments_datetime');
        });

        // Composite index for doctor schedule queries (loadDoctorAppointments, loadMonthAppointments)
        Schema::table('appointments', function (Blueprint $table) {
            $table->index(['doctor_id', 'appointment_datetime'], 'idx_appointments_doctor_datetime');
        });

        // Composite index for patient last-visit queries (loadPreviousProvider)
        Schema::table('appointments', function (Blueprint $table) {
            $table->index(['patient_id', 'appointment_type', 'appointment_datetime'], 'idx_appointments_patient_type_datetime');
        });

        // Composite index for patient_verifications (used by has_pending_verification subquery)
        Schema::table('patient_verifications', function (Blueprint $table) {
            $table->index(['patient_id', 'status'], 'idx_verifications_patient_status');
        });

        // Index on services.service_name for search ordering
        Schema::table('services', function (Blueprint $table) {
            $table->index('service_name', 'idx_services_name');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex('idx_appointments_datetime');
            $table->dropIndex('idx_appointments_doctor_datetime');
            $table->dropIndex('idx_appointments_patient_type_datetime');
        });

        Schema::table('patient_verifications', function (Blueprint $table) {
            $table->dropIndex('idx_verifications_patient_status');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex('idx_services_name');
        });
    }
};
