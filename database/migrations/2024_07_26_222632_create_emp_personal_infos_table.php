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
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id');
            // $table->string('title')->nullable();
            // $table->string('first_name');
            // $table->string('last_name');
            $table->date('date_of_birth')->nullable();
            // $table->string('gender');
            $table->string('blood_group',20)->nullable();
            $table->binary('image')->nullable();
            // $table->binary('signature')->nullable();
            $table->string('nationality',50)->nullable();
            $table->string('religion',50)->nullable();
            $table->enum('marital_status', ['married', 'unmarried']);
            $table->enum('physically_challenge', ['yes', 'no']);
            $table->string('email',100)->nullable();
            // $table->string('mobile_number',20)->nullable();
            $table->string('alternate_mobile_number',20)->nullable();
            $table->string('city',100)->nullable();
            $table->string('state',100)->nullable();
            $table->string('postal_code',10)->nullable();
            $table->string('country',100)->nullable();
            $table->json('qualification')->nullable();
            $table->string('company_name',100)->nullable();
            $table->string('designation',100)->nullable();
            // $table->json('experience')->nullable();
            // $table->string('skill_set')->nullable();
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('passport_no',20)->nullable();
            $table->string('pan_no',10)->nullable();
            $table->string('adhar_no',12)->nullable();
            $table->string('pf_no',20)->nullable();
            $table->string('nick_name',100)->nullable();
            $table->text('biography')->nullable();
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
