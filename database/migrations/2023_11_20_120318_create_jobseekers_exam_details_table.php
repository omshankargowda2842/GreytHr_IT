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
        Schema::create('jobseekers_exam_details', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('job_id');
            $table->date('exam_date');
            $table->time('exam_time');
            $table->text('instructions')->nullable();
            $table->string('company_website')->nullable();
            $table->string('location_link')->nullable();
            $table->string('exam_link')->nullable();
            $table->foreign('user_id')
                ->references('user_id') // Assuming the primary key of the companies table is 'id'
                ->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->foreign('job_id')
                ->references('job_id') // Assuming the primary key of the companies table is 'id'
                ->on('jobs')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->unique(['user_id', 'job_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobseekers_exam_details');
    }
};
