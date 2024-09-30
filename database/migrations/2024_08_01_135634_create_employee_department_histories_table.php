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
        Schema::create('employee_department_histories', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id'); // Ensure this matches the type in 'employee_details'
            $table->string('dept_id')->nullable(); // Ensure this matches the type in 'departments'
            $table->string('sub_dept_id')->nullable(); // Ensure this matches the type in 'sub_departments'
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('manager')->nullable();
            $table->json('job_role')->nullable();
            $table->string('dept_head')->nullable();
            $table->timestamps();

            // Define foreign keys
            $table->foreign('emp_id')->references('emp_id')->on('employee_details')->onDelete('cascade');
            $table->foreign('dept_id')->references('dept_id')->on('emp_departments')->onDelete('cascade');
            $table->foreign('sub_dept_id')->references('sub_dept_id')->on('emp_sub_departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_department_histories');
    }
};
