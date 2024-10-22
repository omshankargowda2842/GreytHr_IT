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
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id',10);
            $table->string('name',100)->nullable();
            $table->string('gender')->nullable();
            $table->binary('image')->nullable();
            $table->string('profession',100)->nullable();
            $table->string('qualification')->nullable();
            $table->date('dob')->nullable();
            $table->string('nationality',50)->nullable();
            $table->string('bld_group',10)->nullable();
            $table->string('adhar_no',12)->nullable();
            $table->string('pan_no',10)->nullable();
            $table->string('religion',50)->nullable();
            $table->string('email',100)->nullable();
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
