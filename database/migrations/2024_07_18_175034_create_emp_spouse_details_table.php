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
        Schema::create('emp_spouse_details', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->string('name')->nullable();
            $table->string('gender')->nullable();
            $table->binary('image')->nullable();
            $table->string('profession')->nullable();
            $table->string('qualification')->nullable();
            $table->date('dob')->nullable();
            $table->string('nationality')->nullable();
            $table->string('bld_group')->nullable();
            $table->string('adhar_no')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('religion')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->json('children')->nullable();
            $table->foreign('emp_id')->references('emp_id')->on('employee_details')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_spouse_details');
    }
};
