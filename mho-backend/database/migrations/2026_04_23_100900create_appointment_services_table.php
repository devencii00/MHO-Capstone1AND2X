<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('appointment_services', function (Blueprint $table) {
    $table->id();

    $table->unsignedBigInteger('appointment_id');
    $table->unsignedBigInteger('service_id');

    $table->enum('status', [
        'pending',
        'in_progress',
        'awaiting_lab',
        'completed',
        'cancelled'
    ])->default('pending');

    $table->unsignedBigInteger('completed_by')->nullable();
    $table->timestamp('completed_at')->nullable();

    $table->foreign('appointment_id')
        ->references('appointment_id')
        ->on('appointments')
        ->cascadeOnDelete();

    $table->foreign('service_id')
        ->references('service_id')
        ->on('services')
        ->cascadeOnDelete();

    $table->foreign('completed_by')
        ->references('user_id')
        ->on('users')
        ->nullOnDelete();

    $table->index('appointment_id');
    $table->index('service_id');
    $table->index('status');
    $table->index('completed_by');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_services');
    }
};
