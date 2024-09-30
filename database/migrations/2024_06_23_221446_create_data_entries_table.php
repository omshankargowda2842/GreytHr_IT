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
        Schema::create('data_entries', function (Blueprint $table) {
            $table->id();
            $table->string('employee_name')->nullable();
            $table->string('employee_title')->nullable();
            $table->string('project_title')->nullable();
            $table->string('employee_email')->nullable();
            $table->string('work_location')->nullable();
            $table->string('project_manager')->nullable();
            $table->string('access_network')->nullable();
            $table->string('po_number')->nullable();
            $table->decimal('hourly_paid', 8, 2)->nullable();
            $table->decimal('mark_up', 5, 2)->nullable();
            $table->decimal('hourly_max', 8, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('sow_end_date')->nullable();
            $table->string('background_check')->nullable();
            $table->string('on_site_resource')->nullable();
            $table->string('vaccination_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_entries');
    }
};
