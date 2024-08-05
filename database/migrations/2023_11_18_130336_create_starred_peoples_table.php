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
            $table->id();
            $table->string('emp_id');
            $table->string('company_id');
            $table->string('name')->nullable();
            $table->string('people_id');
            $table->string('profile')->nullable();
            $table->string('contact_details')->nullable();
            $table->string('category')->nullable();
            $table->string('location')->nullable();
            $table->string('joining_date')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('starred_status')->default('starred');
            $table->unique(['emp_id', 'people_id']);

            $table->foreign('emp_id')
                ->references('emp_id') // Assuming the primary key of the companies table is 'id'
                ->on('employee_details')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->foreign('company_id')
                ->references('company_id') // Assuming the primary key of the companies table is 'id'
                ->on('companies')
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
