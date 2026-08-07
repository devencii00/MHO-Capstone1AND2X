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
     Schema::create('lab_requests', function (Blueprint $table) {

    $table->id('lab_request_id');

    $table->unsignedBigInteger('appointment_service_id');

    
    $table->unsignedBigInteger('requested_by')->nullable();

    $table->enum('status', [
        'pending',
        'queued',
        'called',
        'sample_collected',
        'processing',
        'completed',
        'cancelled'
    ])->default('pending');

    $table->timestamp('requested_at')->useCurrent();

    $table->text('remarks')->nullable();

    $table->timestamps();

    $table->index('appointment_service_id');
    $table->index('requested_by');
    $table->index('status');

    $table->foreign('appointment_service_id')
        ->references('id')
        ->on('appointment_services')
        ->cascadeOnDelete();

    $table->foreign('requested_by')
        ->references('user_id')
        ->on('users')
        ->nullOnDelete();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_requests');
    }
};
