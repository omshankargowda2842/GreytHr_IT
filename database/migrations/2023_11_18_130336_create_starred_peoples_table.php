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
        Schema::create('starred_peoples', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id', 10);
            $table->json('company_id');
            $table->string('name', 100)->nullable();
            $table->string('people_id', 10)->unique();
            $table->binary('profile')->nullable();
            $table->string('contact_details', 20)->nullable();
            $table->string('category', 50)->nullable();
            $table->string('location', 50)->nullable();
            $table->date('joining_date')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('starred_status')->default('starred');
            $table->foreign('emp_id')
                ->references('emp_id') // Assuming the primary key of the companies table is 'id'
                ->on('employee_details')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('starred_peoples');
    }
};
