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
        Schema::create('company_shifts', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('company_id',10);
            $table->string('shift_name',10);
            $table->time('shift_start_time');
            $table->time('shift_end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companyShifts');
    }
};
