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

        Schema::create('salary_revisions', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id',10);
            $table->string('salary');
            $table->date('salary_month');
            $table->date('last_revision_period');
            $table->date('present_revision_period');
            $table->date('revised_monthly_ctc');
            $table->date('previous_monthly_ctc');
            $table->timestamps();
            // Define the foreign key relationship
            $table->foreign('emp_id')->references('emp_id')->on('employee_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_revisions');
    }
};
