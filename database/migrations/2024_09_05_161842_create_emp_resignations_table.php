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
        Schema::create('emp_resignations', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id')->unique(); // Foreign key to employee table
            $table->string('reason')->nullable(); // Reason for resignation
            $table->date('resignation_date'); // Date of resignation
            $table->date('last_working_day')->nullable(); // Last working day
            $table->text('comments')->nullable(); // Additional comments
            $table->binary('signature')->nullable();
            $table->string('status')->default('Pending');
            $table->timestamps();
            $table->foreign('emp_id')
            ->references('emp_id')
            ->on('employee_details')
            ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_resignations');
    }
};
