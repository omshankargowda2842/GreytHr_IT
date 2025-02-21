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
        Schema::create('ticket_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Notification title
            $table->text('message'); // Notification message
            $table->boolean('is_read')->default(false)->nullable(); // Track if the notification has been read
            $table->string('redirect_url'); // URL to redirect the user when the notification is clicked

            // Polymorphic relationship
            $table->unsignedBigInteger('notifiable_id')->nullable(); // ID of the related record
            $table->string('notifiable_type')->nullable(); // Table name or model class

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_notifications');
    }
};
