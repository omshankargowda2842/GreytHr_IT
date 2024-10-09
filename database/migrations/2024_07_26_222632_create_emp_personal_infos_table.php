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
        Schema::create('emp_personal_infos', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->string('title')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            $table->string('gender');
            $table->string('blood_group')->nullable();
            $table->binary('image')->nullable();
            $table->binary('signature')->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();
            $table->enum('marital_status', ['married', 'unmarried']);
            $table->enum('physically_challenge', ['yes', 'no']);
            $table->string('email')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('alternate_mobile_number')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->json('qualification')->nullable();
            $table->string('company_name')->nullable();
            $table->string('designation')->nullable();
            $table->json('experience')->nullable();
            $table->string('skill_set')->nullable();
            $table->text('present_address')->nullable();
            $table->text('permenant_address')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('pan_no')->nullable();
            $table->string('adhar_no')->nullable();
            $table->string('pf_no')->nullable();
            $table->string('nick_name')->nullable();
            $table->string('biography')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linked_in')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->foreign('emp_id')->references('emp_id')->on('employee_details')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_personal_infos');
    }
};
