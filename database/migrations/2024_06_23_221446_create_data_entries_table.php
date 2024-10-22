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
            $table->smallInteger('id')->autoIncrement();
            $table->string('employee_name','100')->nullable();
            $table->string('employee_title','100')->nullable();
            $table->string('project_title','50')->nullable();
            $table->string('employee_email','100')->nullable();
            $table->string('work_location','100')->nullable();
            $table->string('project_manager','50')->nullable();
            $table->string('access_network','100')->nullable();
            $table->string('po_number','50')->nullable();
            $table->decimal('hourly_paid', 8, 2,)->nullable();
            $table->decimal('mark_up', 5, 2)->nullable();
            $table->decimal('hourly_max', 8, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('sow_end_date')->nullable();
            $table->string('background_check','100')->nullable();
            $table->string('on_site_resource'.'100')->nullable();
            $table->string('vaccination_status','10')->nullable();
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
