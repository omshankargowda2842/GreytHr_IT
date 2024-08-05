<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('time_sheets', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->json('date_and_day_with_tasks');
            $table->string('submission_status')->default('submitted')->nullable();
            $table->string('approval_status_for_manager')->default('pending')->nullable();
            $table->string('approval_status_for_hr')->default('pending')->nullable();
            $table->string('reject_reason_for_manager')->default('pending')->nullable();
            $table->string('resubmit_reason_for_manager')->default('pending')->nullable();
            $table->string('reject_reason_for_hr')->default('pending')->nullable();
            $table->string('resubmit_reason_for_hr')->default('pending')->nullable();
            $table->timestamps();
            $table->foreign('emp_id')
            ->references('emp_id')
            ->on('employee_details')
            ->onDelete('restrict')
            ->onUpdate('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_sheets');
    }
};
