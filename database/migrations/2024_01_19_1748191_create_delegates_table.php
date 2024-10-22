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
        Schema::create('delegates', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id',10);
            $table->enum('workflow', ['Delegate All Workflow', 'Attendance Regularization', 'Confirmation', 'Resignations', 'Leave', 'Leave Cancel', 'Leave Comp Off', 'Restricted Holiday Leave', 'Help Desk']);
            $table->date('from_date');
            $table->date('to_date');
            $table->string('delegate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delegates');
    }
};

