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
        Schema::create('emp_parent_details', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->string('father_first_name')->nullable();
            $table->string('father_last_name')->nullable();
            $table->string('mother_first_name')->nullable();
            $table->string('mother_last_name')->nullable();
            $table->date('father_dob')->nullable();
            $table->date('mother_dob')->nullable();
            $table->string('father_address')->nullable();
            $table->string('mother_address')->nullable();
            $table->string('father_city')->nullable();
            $table->string('mother_city')->nullable();
            $table->string('father_state')->nullable();
            $table->string('mother_state')->nullable();
            $table->string('father_country')->nullable();
            $table->string('mother_country')->nullable();
            $table->string('father_email')->unique()->nullable();
            $table->string('mother_email')->unique()->nullable();
            $table->string('father_phone')->unique()->nullable();
            $table->string('mother_phone')->unique()->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->binary('father_image')->nullable();
            $table->binary('mother_image')->nullable();
            $table->string('father_blood_group')->nullable();
            $table->string('mother_blood_group')->nullable();
            $table->string('father_nationality')->nullable();
            $table->string('mother_nationality')->nullable();
            $table->string('father_religion')->nullable();
            $table->string('mother_religion')->nullable();
            $table->foreign('emp_id')->references('emp_id')->on('employee_details')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_parent_details');
    }
};
