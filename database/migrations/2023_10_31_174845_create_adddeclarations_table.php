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
        Schema::create('adddeclarations', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->decimal('5_years_fixed_deposit');
            $table->decimal('5_years_deposit');
            $table->decimal('contribution_to_pension_fund');
            $table->decimal('deposit_in_nsc');
            $table->decimal('deposit_in_nss');
            $table->decimal('interest_on_nsc_reinvested');
            $table->decimal('equity');
            $table->decimal('life_insurance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adddeclarations');
    }
};
