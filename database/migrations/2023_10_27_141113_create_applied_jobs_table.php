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
        Schema::create('applied_jobs', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('user_id', 10)->unique();
            $table->string('applied_to', 100);
            $table->string('job_id', 10)->unique();
            $table->string('job_title', 100);
            $table->string('company_name', 100);
            $table->string('application_status')->nullable();
            $table->string('resume');
            $table->timestamps();
            $table->foreign('job_id')
                ->references('job_id')
                ->on('jobs')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applied_jobs');
    }
};
