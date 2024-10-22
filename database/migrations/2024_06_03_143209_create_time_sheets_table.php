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
            // Use the appropriate data types
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id', 10); // Ensure this matches the type in employee_details
            $table->date('start_date');
            $table->date('end_date');
            $table->json('date_and_day_with_tasks');
            $table->string('time_sheet_type', 20)->nullable();

            // Ensure these match the status_code type in status_types table
            $table->tinyInteger('submission_status')->default(13)->nullable();
            $table->tinyInteger('approval_status_for_manager')->default(5)->nullable();
            $table->tinyInteger('approval_status_for_hr')->default(5)->nullable();

            $table->string('reject_reason_for_manager')->nullable();
            $table->string('resubmit_reason_for_manager')->nullable();
            $table->string('reject_reason_for_hr')->nullable();
            $table->string('resubmit_reason_for_hr')->nullable();
            $table->timestamps();

            // Foreign key constraints with matching types
            $table->foreign('emp_id')
                  ->references('emp_id')
                  ->on('employee_details')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');

            $table->foreign('submission_status')
                  ->references('status_code')
                  ->on('status_types')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');

            $table->foreign('approval_status_for_manager')
                  ->references('status_code')
                  ->on('status_types')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');

            $table->foreign('approval_status_for_hr')
                  ->references('status_code')
                  ->on('status_types')
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
