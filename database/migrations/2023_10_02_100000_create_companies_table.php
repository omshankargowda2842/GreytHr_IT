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
            $table->id();
            $table->string('company_id')->unique(); // Primary key
            $table->string('company_name');
            $table->string('ceo_name')->nullable();
            $table->string('company_website')->nullable();
            $table->string('company_type')->nullable();
            $table->string('company_industry')->nullable();
            $table->string('company_description')->nullable();
            $table->string('company_present_address')->nullable();
            $table->string('company_perminent_address')->nullable();
            $table->date('company_registration_date')->nullable();
            $table->string('company_registration_no')->unique();
            $table->string('company_logo')->nullable();
            $table->string('contact_email')->unique();
            $table->string('contact_phone')->unique();
            $table->string('password')->nullable();
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
