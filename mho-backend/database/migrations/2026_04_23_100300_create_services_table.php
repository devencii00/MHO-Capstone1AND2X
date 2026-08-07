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
        Schema::create('services', function (Blueprint $table) {
            $table->id('service_id');
            $table->string('service_name')->nullable();
            $table->enum('service_category', ['Consultation','Laboratory'])->default('Consultation');
            $table->boolean('requires_consultation')->default(true);
            $table->text('description')->nullable();   
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('duration_minutes')->default(30);
            $table->boolean('is_active')->default(true);
            

            $table->index('service_name', 'idx_services_name');
            $table->index('service_category');
            $table->index('requires_consultation');
            $table->index('is_active');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
