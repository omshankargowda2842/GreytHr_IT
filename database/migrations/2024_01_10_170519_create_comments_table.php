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
        Schema::create('comments', function (Blueprint $table) {
        $table->id();
        $table->string('card_id');
        $table->string('emp_id')->nullable();
        $table->string('hr_emp_id')->nullable();
        $table->string('comment');
        $table->foreign('emp_id')
        ->references('emp_id')
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
        Schema::dropIfExists('comments');
    }
};
