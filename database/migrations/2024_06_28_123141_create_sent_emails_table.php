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
        Schema::create('sent_emails', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('to_email','100')->nullable();
            $table->string('cc_email','100')->nullable();
            $table->string('subject')->nullable();
            $table->string('file_path')->nullable();
            $table->string('scheduled_time','50')->nullable();
            $table->string('status','10')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sent_emails');
    }
};
