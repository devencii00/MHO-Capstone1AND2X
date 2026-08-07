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
      Schema::create('inventory_transactions', function (Blueprint $table) {

    $table->id('inventory_transaction_id');

    $table->unsignedBigInteger('inventory_id');
    $table->unsignedBigInteger('user_id');

    $table->enum('type', [
        'in',
        'out',
        'adjustment',
        'damaged',
        'expired',
        'donation'
    ]);

    $table->integer('quantity');

    $table->integer('previous_quantity');

    $table->integer('new_quantity');

    $table->text('reason')->nullable();

    $table->string('reference_number')->nullable();

    $table->timestamps();

    $table->index('inventory_id');
    $table->index('user_id');
    $table->index('type');

    $table->foreign('inventory_id')
        ->references('inventory_id')
        ->on('inventory')
        ->cascadeOnDelete();

    $table->foreign('user_id')
        ->references('user_id')
        ->on('users')
        ->restrictOnDelete();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
