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
        Schema::create('emp_salaries', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id',10);
            $table->string('salary'); // Use string for encrypted salary
            $table->date('effective_date');
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->foreign('emp_id')->references('emp_id')->on('employee_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_salaries');
    }
};
