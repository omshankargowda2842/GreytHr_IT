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
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id', 10); // Foreign key to employee table
            $table->text('reason')->nullable(); // Reason for resignation
            $table->date('resignation_date'); // Date of resignation
            $table->date('last_working_day')->nullable(); // Last working day
            $table->text('comments')->nullable(); // Additional comments
            $table->binary('signature')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->string('file_name')->nullable();
            $table->tinyInteger('status')->default(5);
            $table->timestamps();
            $table->foreign('emp_id')
                ->references('emp_id')
                ->on('employee_details')
                ->onDelete('restrict');

            $table->foreign('status')
                ->references('status_code')
                ->on('status_types')
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
