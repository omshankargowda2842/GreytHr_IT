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
        Schema::create('help_desks', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->string('category'); 
            $table->string('mail'); 
            $table->string('distributor_name'); 
            $table->string('mobile');
            $table->string('subject');
            $table->text('description'); 
            $table->string('file_path')->nullable(); // Path to attached file (nullable)
            $table->string('cc_to')->nullable(); // CC to field (nullable)
            $table->string('status')->default('Recent'); // CC to field (nullable)
            $table->enum('priority', ['High', 'Medium', 'Low']); // Priority field with enum values 
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
        Schema::dropIfExists('help_desks');
    }
};
