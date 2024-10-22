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
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id', 10)->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload')->nullable();
            $table->string('country', 20)->nullable();
            $table->string('city', 20)->nullable();
            $table->string('iso_code', 10)->nullable();
            $table->string('state', 20)->nullable();
            $table->string('state_name', 20)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('timezone', 20)->nullable();
            $table->string('continent', 20)->nullable();
            $table->string('currency', 10)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('last_activity')->index();
            $table->string('device_type', 20)->nullable();
            $table->string('user_type', 20)->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
