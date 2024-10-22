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
        Schema::create('emp_experiences', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id',10);
            $table->json('experience');
            $table->timestamps();
            $table->foreign('emp_id')->references('emp_id')->on('employee_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_experiences');
    }
};
