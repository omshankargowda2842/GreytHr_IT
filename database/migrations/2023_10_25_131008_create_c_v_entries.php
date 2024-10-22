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
            $table->smallInteger('id')->autoIncrement();
            $table->string('first_name',100);
            $table->string('last_name',100);
            $table->string('email',100)->unique();
            $table->string('phone',20)->unique();
            $table->string('country',20);
            $table->string('city',20);
            $table->string('address');
            $table->date('date_of_birth',);
            $table->binary('image');
            $table->text('technical_skills');
            $table->text('summary');
            $table->text('languages');
            $table->json('education'); // JSON column for education records
            $table->json('work_experience'); // JSON column for work experience records
            $table->timestamps();
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
