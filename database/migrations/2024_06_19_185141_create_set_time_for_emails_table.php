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
        Schema::create('set_time_for_emails', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index(); // Name of the queue the job belongs to
            $table->longText('payload'); // Serialized representation of the job and its data
            $table->unsignedTinyInteger('attempts')->default(0); // Number of attempts made to process the job
            $table->string('reserved_at')->nullable(); // Timestamp when the job was reserved by a worker
            $table->string('available_at'); // Timestamp when the job will become available to be processed
            $table->string('created_at'); // Timestamp when the job was created
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_time_for_emails');
    }
};
