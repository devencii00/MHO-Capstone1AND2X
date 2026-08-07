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
      Schema::create('lab_results', function (Blueprint $table) {

    $table->id('lab_result_id');

    $table->unsignedBigInteger('lab_request_id');

   
    $table->unsignedBigInteger('performed_by')->nullable();

    $table->text('result')->nullable();

    $table->longText('findings')->nullable();

    $table->text('notes')->nullable();

    $table->string('attachment_path')->nullable();

    $table->text('staff_remarks')->nullable();

    $table->enum('status', [
        'draft',
        'verified',
        'released',
        'archived'
    ])->default('draft');

    $table->timestamp('completed_at')->nullable();

    $table->timestamp('released_at')->nullable();

    $table->timestamps();

    $table->index('lab_request_id');
    $table->index('performed_by');
    $table->index('status');
    $table->index('completed_at');

    $table->foreign('lab_request_id')
        ->references('lab_request_id')
        ->on('lab_requests')
        ->cascadeOnDelete();

    $table->foreign('performed_by')
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
        Schema::dropIfExists('lab_results');
    }
};
