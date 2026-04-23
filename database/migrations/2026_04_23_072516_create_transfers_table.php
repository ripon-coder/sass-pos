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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained("tenants")->cascadeOnDelete();
            $table->foreignId('from_branch_id')->constrained("branches")->cascadeOnDelete();
            $table->foreignId('to_branch_id')->constrained("branches")->cascadeOnDelete();
            $table->enum('status', ['pending', 'approved', 'completed', 'declined'])->default('pending');
            $table->string('approved_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
