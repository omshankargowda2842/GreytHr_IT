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
        Schema::create('other_chapters', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id', 10);
            $table->decimal('intrest_on_housing');
            $table->decimal('intrest_on_loan');
            $table->decimal('contribution_to_pension_fund');
            $table->decimal('deposit_in_nsc');
            $table->decimal('deposit_in_nss');
            $table->decimal('interest_on_nsc_reinvested');
            $table->decimal('superannuation');
            $table->decimal('donation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otherchapters');
    }
};
