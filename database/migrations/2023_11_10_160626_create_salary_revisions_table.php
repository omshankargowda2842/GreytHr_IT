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
            $table->id();
            $table->string('emp_id')->unique();
            $table->string('company_id');
            $table->decimal('salary', 10, 2);
            $table->date('salary_month');
            $table->date('last_revision_period');
            $table->date('present_revision_period');
            $table->decimal('revised_monthly_ctc', 10, 2);
            $table->decimal('previous_monthly_ctc', 10, 2);
            $table->timestamps();
            // Define the foreign key relationship
            $table->foreign('emp_id')->references('emp_id')->on('employee_details');
            $table->foreign('company_id')->references('company_id')->on('companies');
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
