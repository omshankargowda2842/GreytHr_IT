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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->string('task_name');
            $table->string('assignee');
            $table->enum('priority', ['High', 'Medium', 'Low']); 
            $table->string('due_date');
            $table->string('tags'); 
            $table->string('followers');
            $table->string('subject');
            $table->text('description');
            $table->string('file_path')->nullable();
            $table->string('status')->default('Open'); // CC to field (nullable)
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
        Schema::dropIfExists('tasks');
    }
};
