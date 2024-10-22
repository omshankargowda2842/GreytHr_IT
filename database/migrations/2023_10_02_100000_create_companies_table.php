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
            $table->smallInteger('id')->autoIncrement();
            $table->string('company_id', 20)->unique(); // Primary key
            $table->string('company_name', 100);
            $table->string('ceo_name', 100)->nullable();
            $table->string('company_website')->nullable();
            $table->string('company_type', 100)->nullable();
            $table->string('company_industry', 100)->nullable();
            $table->string('company_description')->nullable();
            $table->string('company_present_address')->nullable();
            $table->string('company_permanent_address')->nullable();
            $table->string('branch_locations')->nullable();
            $table->date('company_registration_date')->nullable();
            $table->string('company_registration_no', 100)->unique();
            $table->string('company_logo')->nullable();
            $table->string('contact_email', 100)->unique();
            $table->string('email_domain');
            $table->string('parent_company_id', 20);
            $table->enum('is_parent', ['yes', 'no'])->default('no');
            $table->string('contact_phone', 20)->unique();
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
