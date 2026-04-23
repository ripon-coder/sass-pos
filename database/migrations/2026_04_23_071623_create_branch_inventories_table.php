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
        Schema::create('branch_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained("branches")->cascadeOnDelete();
            $table->foreignId('variant_id')->constrained("product_variants")->cascadeOnDelete();
            $table->decimal('price',10,2)->default(0);
            $table->unsignedInteger('stock')->default(0);
            $table->decimal('low_stock_alert_qty',10,2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->unique(['branch_id', 'variant_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_inventories');
    }
};
