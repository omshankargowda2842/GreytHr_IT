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
        Schema::create('itstatement', function (Blueprint $table) {
            $table->id();
            $table->enum('montly_income_type', ['basic', 'hra', 'conveyance', 'medical', 'total']);
            $table->string('total');
            $table->string('Jan_2023');
            $table->string('Feb_2023');
            $table->string('Mar_2023');
            $table->string('Apr_2023');
            $table->string('May_2023');
            $table->string('Jun_2023');
            $table->string('Jul_2023');
            $table->string('Aug_2023');
            $table->string('Sep_2023');
            $table->string('Oct_2023');
            $table->string('Nov_2023');
            $table->string('Dec_2023');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itstatement');
    }
};
