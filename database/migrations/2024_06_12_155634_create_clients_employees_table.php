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
        Schema::create('clients_employees', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('client_id',10);
            $table->string('project_name',100);
            $table->string('emp_id',10);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
            $table->foreign('client_id')
            ->references('client_id') // Assuming the primary key of the companies table is 'id'
            ->on('clients')
            ->onDelete('restrict')
            ->onUpdate('cascade');
            $table->foreign('emp_id')
            ->references('emp_id') // Assuming the primary key of the companies table is 'id'
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
        Schema::dropIfExists('clients_employees');
    }
};
