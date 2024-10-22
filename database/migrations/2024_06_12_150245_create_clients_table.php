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
        Schema::create('clients', function (Blueprint $table) {
            $table->string('client_id', 10)->primary(); // Primary key
            $table->string('client_name', 100);
            $table->string('hr_name', 100);
            $table->string('client_address1');
            $table->string('client_address2');
            $table->date('client_registration_date');
            $table->string('client_logo')->nullable();
            $table->string('password');
            $table->string('contact_email', 100);
            $table->string('contact_phone', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
