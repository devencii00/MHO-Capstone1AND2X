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
      Schema::create('inventory', function (Blueprint $table) {

    $table->id('inventory_id');

    $table->string('item_name', 150);
    $table->string('category', 100);

    $table->string('sku', 100)->unique()->nullable();

    $table->text('description')->nullable();

    $table->integer('quantity')->default(0);

    $table->integer('min_stock_level')->default(0);
    $table->integer('max_stock_level')->nullable();

    $table->string('unit', 30)->nullable();

    $table->decimal('unit_cost', 10, 2)->default(0);
    $table->decimal('selling_price', 10, 2)->default(0);

    $table->string('supplier')->nullable();

    $table->date('expiry_date')->nullable();
    $table->boolean('track_expiry')->default(false);

    $table->string('location')->nullable();

    $table->enum('status', [
        'available',
        'low_stock',
        'out_of_stock',
        'expired',
        'inactive'
    ])->default('available');

    $table->timestamps();

    $table->index('category');
    $table->index('status');
    $table->index('expiry_date');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
