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
        Schema::create('chatings', function (Blueprint $table) {
            $table->id();
            $table->string('sender_id');
            $table->string('receiver_id');
            $table->softDeletes();
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('sender_id')->references('emp_id')->on('employee_details')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('receiver_id')->references('emp_id')->on('employee_details')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatings');
    }
};
