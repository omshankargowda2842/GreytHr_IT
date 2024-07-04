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
        Schema::create('companies', function (Blueprint $table) {
            $table->string('company_id')->primary(); // Primary key
            $table->string('company_name');
            $table->string('hr_name');
            $table->string('company_address1');
            $table->string('company_address2');
            $table->date('company_registration_date');
            $table->string('company_logo');
            $table->string('password');
            $table->string('contact_email');
            $table->string('contact_phone');
            $table->timestamps(); // Created_at and updated_at columns
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
