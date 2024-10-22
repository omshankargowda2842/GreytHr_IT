<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_details', function (Blueprint $table) {
            $table->string('emp_id',10)->primary();
            $table->string('first_name',100);
            $table->string('last_name',100);
            $table->enum('gender', ['MALE', 'FEMALE', 'OTHER'])->nullable();
            $table->string('email',100)->unique()->nullable();
            $table->json('company_id');
            $table->binary('image')->nullable();
            $table->string('dept_id',10)->nullable();
            $table->string('sub_dept_id',10)->nullable();
            $table->date('hire_date')->nullable();
            $table->string('employee_type',100)->nullable();
            $table->string('job_role',100)->nullable();
            $table->string('manager_id',10)->nullable();
            $table->string('dept_head',10)->nullable();
            $table->enum('role', ['user', 'admin', 'super_admin'])->default('user'); //Define ENUM for roles
            $table->enum('employee_status', ['active', 'on-leave', 'terminated', 'resigned', 'on-probation'])->default('active');
            $table->string('emergency_contact',20)->nullable();
            $table->string('password')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->enum('inter_emp', ['yes', 'no']);
            $table->string('job_location',50)->nullable();
            $table->string('experience',20)->nullable();
            $table->string('time_zone',50)->nullable();
            $table->string('is_starred',10)->nullable();
            $table->string('job_mode',20)->nullable();
            $table->json('emp_domain')->nullable();
            $table->string('notice_period',10)->nullable();
            $table->date('resignation_date')->nullable();
            $table->text('resignation_reason')->nullable();
            $table->string('extension',10)->nullable();
            $table->string('shift_type',10)->nullable();
            // $table->string('shift_start_time')->nullable();
            // $table->string('shift_end_time')->nullable();
            $table->string('probation_Period',10)->default('30');
            $table->date('confirmation_date')->nullable();
            $table->string('referral',100)->nullable();
            $table->string('service_age',20)->nullable();
            $table->foreign('dept_id')->references('dept_id')->on('emp_departments')->onDelete('cascade');
            $table->foreign('sub_dept_id')->references('sub_dept_id')->on('emp_sub_departments')->onDelete('cascade');
            $table->rememberToken();
            $table->timestamps();
        });


        DB::statement("
        CREATE TRIGGER generate_emp_id BEFORE INSERT ON employee_details FOR EACH ROW
        BEGIN
            DECLARE max_id INT;

            -- Fixed prefix for emp_id
            SET @prefix = 'XSS-';

            IF NEW.emp_id IS NULL OR NEW.emp_id = '' THEN
                -- Find the maximum existing employee id
                SELECT MAX(CAST(SUBSTRING(emp_id COLLATE utf8mb4_unicode_ci, LENGTH(@prefix) + 1) AS UNSIGNED)) INTO max_id FROM employee_details WHERE emp_id LIKE CONCAT(@prefix, '%') COLLATE utf8mb4_unicode_ci;

                IF max_id IS NOT NULL THEN
                    -- Existing employees, increment the counter
                    SET NEW.emp_id = CONCAT(@prefix, LPAD(max_id + 1, 4, '0'));
                ELSE
                    -- No existing employees, start the counter from 0001
                    SET NEW.emp_id = CONCAT(@prefix, '0001');
                END IF;
            END IF;
        END;
    ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        // Drop the trigger if it exists
        DB::unprepared('DROP TRIGGER IF EXISTS generate_emp_id');

        // Drop the table
        Schema::dropIfExists('employee_details');
    }
};
