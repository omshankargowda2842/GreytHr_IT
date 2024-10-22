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
        Schema::create('vendors_submit_cv_to_hr', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('user_id', 10);
            $table->string('job_id', 10);
            $table->string('submited_to');
            $table->json('cv');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('restrict')
                ->onUpdate('cascade');;
            $table->foreign('job_id')->references('job_id')->on('jobs')->onDelete('restrict')
                ->onUpdate('cascade');;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors_submit_cv_to_hr');
    }
};
