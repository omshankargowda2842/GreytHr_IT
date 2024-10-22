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
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id', 10);
            $table->string('father_first_name', 100)->nullable();
            $table->string('father_last_name', 100)->nullable();
            $table->string('mother_first_name', 100)->nullable();
            $table->string('mother_last_name', 100)->nullable();
            $table->date('father_dob')->nullable();
            $table->date('mother_dob')->nullable();
            $table->string('father_address', 100)->nullable();
            $table->string('mother_address', 100)->nullable();
            $table->string('father_city', 100)->nullable();
            $table->string('mother_city', 100)->nullable();
            $table->string('father_state', 100)->nullable();
            $table->string('mother_state', 100)->nullable();
            $table->string('father_country', 100)->nullable();
            $table->string('mother_country', 100)->nullable();
            $table->string('father_email', 200)->nullable();
            $table->string('mother_email', 200)->nullable();
            $table->string('father_phone', 20)->nullable();
            $table->string('mother_phone', 20)->nullable();
            $table->string('father_occupation', 20)->nullable();
            $table->string('mother_occupation', 20)->nullable();
            $table->binary('father_image')->nullable();
            $table->binary('mother_image')->nullable();
            $table->string('father_blood_group', 10)->nullable();
            $table->string('mother_blood_group', 10)->nullable();
            $table->string('father_nationality', 20)->nullable();
            $table->string('mother_nationality', 20)->nullable();
            $table->string('father_religion', 20)->nullable();
            $table->string('mother_religion', 20)->nullable();
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
