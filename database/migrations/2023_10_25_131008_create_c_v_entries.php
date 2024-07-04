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
        Schema::create('c_v_entries', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('country');
            $table->string('city');
            $table->string('address');
            $table->date('date_of_birth');
            $table->string('image');
            $table->text('technical_skills');
            $table->text('summary');
            $table->text('languages');
            $table->json('education'); // JSON column for education records
            $table->json('work_experience'); // JSON column for work experience records
            $table->timestamps();
            $table->unique(['email', 'date_of_birth']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_v_entries');
    }
};
