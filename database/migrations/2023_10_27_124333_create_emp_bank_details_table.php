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
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id',10);
            $table->string('bank_name',100);
            $table->string('bank_branch',100);
            $table->string('account_number',20);
            $table->string('ifsc_code',15);
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
