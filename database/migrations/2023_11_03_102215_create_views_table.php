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
        Schema::create('views', function (Blueprint $table) {
            $table->id();
            $table->string('location');
            $table->string('department');
            $table->string('reporting_manager');
            $table->string('total_experience');
            $table->date('join_date');
            $table->date('confirmation_initiated');
            $table->date('confirmation_due_date');
            $table->date('date');
            $table->time('time');
            $table->date('confirmation_initiated_date');
            $table->time('confirmation_initiated_time');
            $table->date('confirmation_due_date1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('views');
    }
};
