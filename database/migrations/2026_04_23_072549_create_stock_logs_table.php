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
        Schema::create('stock_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained("branches")->cascadeOnDelete();
            $table->foreignId('variant_id')->constrained("product_variants")->cascadeOnDelete();
            $table->enum('type', ['sale', 'purchase', 'transfer', 'adjustment'])->default('purchase');
            $table->integer('quantity')->default(0);
            $table->string('reference_id')->nullable()->comment('id of the order, purchase, etc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_logs');
    }
};
