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
        Schema::create('regularisation_dates', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->json('regularisation_entries');
            $table->integer('is_withdraw');
            $table->string('employee_remarks')->nullable();
            $table->string('approver_remarks')->nullable();
            $table->date('regularisation_date')->nullable();
            $table->dateTime('approved_date')->nullable();
            $table->dateTime('rejected_date')->nullable();
            $table->dateTime('withdraw_date')->nullable();
            $table->enum('status', ['approved', 'pending','rejected'])->default('pending');
            $table->string('approved_by')->nullable();
            $table->string('rejected_by')->nullable();
            $table->string('mail_sent')->nullable();
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
        Schema::dropIfExists('regularisation_dates');
    }
};
