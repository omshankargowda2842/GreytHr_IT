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
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id');
            $table->string('category_type')->default('Leave');
            $table->enum('leave_type', ['Casual Leave Probation ', 'Maternity Leave', 'Loss Of Pay','Sick Leave','Marriage Leave','Casual Leave','Petarnity Leave','Work From Home']);
            $table->date('from_date');
            $table->string('from_session');
            $table->string('to_session');
            $table->date('to_date');
            $table->json('file_paths')->nullable();
            $table->string('applying_to');
            $table->json('cc_to')->nullable();
            $table->string('status')->default('Pending');
            $table->string('cancel_status')->default('Pending');
            $table->string('contact_details');
            $table->text('reason');
            $table->boolean('is_read')->default(false);

            // $table->enum('sick_leave', ['yes', 'no'])->default('no');
            // $table->enum('casual_leave', ['yes', 'no'])->default('no');
            $table->timestamps();
            $table->foreign('emp_id')
                ->references('emp_id')
                ->on('employee_details')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_applications');
    }
};
