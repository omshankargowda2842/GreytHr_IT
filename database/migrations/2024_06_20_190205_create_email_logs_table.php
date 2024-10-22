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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('subject');
            $table->timestamp('scheduled_at')->nullable();
            $table->json('to'); // Store multiple "To" emails as comma-separated values
            $table->json('cc')->nullable();
            $table->json('files');
            $table->string('scheduled_status')->default(5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
