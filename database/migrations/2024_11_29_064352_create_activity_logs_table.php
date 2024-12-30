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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action')->nullable(); // What action was performed (e.g., 'Updated Status', 'Assigned Task')
            $table->text('details')->nullable(); // A more detailed description of the action (e.g., 'Status updated to Pending')
            $table->string('performed_by')->nullable(); // The user who performed the action (employee name or ID)
            $table->string('request_type')->nullable(); // Type of the request (e.g., 'Incident Request')
            $table->string('request_id'); // ID of the related request
            $table->string('impact')->nullable(); // Impact level (e.g., 'High', 'Medium', 'Low')
            $table->string('opened_by')->nullable(); // Name or ID of the person who opened the request
            $table->string('priority')->nullable(); // Priority level (e.g., 'High', 'Medium', 'Low')
            $table->string('state')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
