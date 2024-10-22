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
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id', 10);
            $table->json('regularisation_entries');
            $table->tinyInteger('is_withdraw');
            $table->string('employee_remarks')->nullable();
            $table->string('approver_remarks')->nullable();
            $table->date('regularisation_date')->nullable();
            $table->dateTime('approved_date')->nullable();
            $table->dateTime('rejected_date')->nullable();
            $table->dateTime('withdraw_date')->nullable();
            $table->smallInteger('status')->default(0);
            $table->string('approved_by', 100)->nullable();
            $table->string('rejected_by', 100)->nullable();
            $table->string('mail_sent', 200)->nullable();
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
