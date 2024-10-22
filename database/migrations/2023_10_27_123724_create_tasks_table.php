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
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id', 10);
            $table->string('task_name',100)->nullable();
            $table->string('assignee');
            $table->enum('priority', ['High', 'Medium', 'Low']);
            $table->date('due_date');
            $table->string('reopened_date', 10)->nullable();
            $table->string('tags', 100)->nullable();
            $table->string('client_id', 20)->nullable();
            $table->string('project_name', 100)->nullable();
            $table->string('followers')->nullable();
            $table->string('subject',100)->nullable();
            $table->text('description')->nullable();
            $table->binary('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('status', 10)->default('Open'); // CC to field (nullable)
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
