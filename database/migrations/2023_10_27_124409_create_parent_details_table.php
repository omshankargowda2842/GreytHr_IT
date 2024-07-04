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
        Schema::create('parent_details', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->string('father_first_name');
            $table->string('father_last_name');
            $table->string('mother_first_name');
            $table->string('mother_last_name');
            $table->date('father_dob');
            $table->date('mother_dob');
            $table->string('father_address');
            $table->string('mother_address');
            $table->string('father_city');
            $table->string('mother_city');
            $table->string('father_state');
            $table->string('mother_state');
            $table->string('father_country');
            $table->string('mother_country');
            $table->string('father_email')->unique();;
            $table->string('mother_email')->unique();;
            $table->string('father_phone')->unique();;
            $table->string('mother_phone')->unique();;
            $table->string('father_occupation');
            $table->string('mother_occupation');
            $table->string('father_image');
            $table->string('mother_image');
            $table->string('father_blood_group');
            $table->string('mother_blood_group');
            $table->string('father_nationality');
            $table->string('mother_nationality');
            $table->string('father_religion');
            $table->string('mother_religion');
            $table->foreign('emp_id')->references('emp_id')->on('employee_details')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_details');
    }
};
