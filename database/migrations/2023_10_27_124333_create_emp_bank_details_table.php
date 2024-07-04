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
        Schema::create('emp_bank_details', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->string('bank_name');
            $table->string('bank_branch');
            $table->string('account_number')->unique();
            $table->string('ifsc_code');
            $table->string('bank_address');
            $table->foreign('emp_id')->references('emp_id')->on('employee_details')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_bank_details');
    }
};
