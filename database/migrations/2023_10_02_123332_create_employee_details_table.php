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
            $table->string('emp_id')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('company_id')->nullable();
            $table->binary('image')->nullable();
            $table->string('dept_id')->nullable();
            $table->string('sub_dept_id')->nullable();
            $table->date('hire_date')->nullable();
            $table->string('employee_type')->nullable();
            $table->string('job_role')->nullable();
            $table->string('manager_id')->nullable();
            $table->string('dept_head')->nullable();
            $table->enum('employee_status', ['active', 'on-leave', 'terminated','resigned'])->default('active');
            $table->string('emergency_contact')->unique()->nullable();
            $table->string('password')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->enum('inter_emp', ['yes', 'no']);
            $table->string('job_location')->nullable();
            $table->string('experience')->nullable();
            $table->string('time_zone')->nullable();
            $table->string('is_starred')->nullable();
            $table->string('job_mode')->nullable();
            $table->string('emp_domain')->nullable();
            $table->string('notice_period')->nullable();
            $table->string('resignation_date')->nullable();
            $table->string('resignation_reason')->nullable();
            $table->string('extension')->nullable();
            $table->string('shift_type')->nullable();
            $table->string('shift_start_time')->nullable();
            $table->string('shift_end_time')->nullable();
            $table->string('probation_Period')->nullable();
            $table->json('confirmation_date')->nullable();
            $table->string('referral')->nullable();
            $table->string('service_age')->nullable();
            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('cascade');
            $table->foreign('dept_id')->references('dept_id')->on('emp_departments')->onDelete('cascade');
            $table->foreign('sub_dept_id')->references('sub_dept_id')->on('emp_sub_departments')->onDelete('cascade');
            $table->timestamps();
        });


        $triggerSQL = <<<SQL
        CREATE TRIGGER generate_emp_id BEFORE INSERT ON employee_details FOR EACH ROW
        BEGIN
            DECLARE max_id INT;

            -- Fixed prefix for emp_id
            SET @prefix = 'XSS-';

            IF NEW.emp_id IS NULL OR NEW.emp_id = '' THEN
                -- Find the maximum existing employee id
                SELECT MAX(CAST(SUBSTRING(emp_id, LENGTH(@prefix) + 1) AS UNSIGNED)) INTO max_id FROM employee_details WHERE emp_id LIKE CONCAT(@prefix, '%');

                IF max_id IS NOT NULL THEN
                    -- Existing employees, increment the counter
                    SET NEW.emp_id = CONCAT(@prefix, LPAD(max_id + 1, 4, '0'));
                ELSE
                    -- No existing employees, start the counter from 0001
                    SET NEW.emp_id = CONCAT(@prefix, '0001');
                END IF;
            END IF;
        END;
        SQL;

        DB::unprepared($triggerSQL);


        // $triggerSQL = <<<SQL
        // CREATE TRIGGER generate_emp_id BEFORE INSERT ON employee_details FOR EACH ROW
        // BEGIN
        //     DECLARE company_count INT;

        //     IF TRIM(IFNULL(NEW.company_name, '')) != '' THEN
        //         -- Check if the company name has more than one word
        //         IF LOCATE(' ', NEW.company_name) > 0 THEN
        //             -- More than one word, take the first word and use prefixes for the remaining words
        //             SET @first_word = UPPER(SUBSTRING_INDEX(NEW.company_name, ' ', 1));
        //             SET @remaining_words = UPPER(SUBSTRING(SUBSTRING_INDEX(NEW.company_name, ' ', -1), 1, 3));

        //             -- Count the number of existing employees with the same company name
        //             SELECT COUNT(*) INTO company_count FROM employee_details WHERE company_name = NEW.company_name;
        //             SET NEW.emp_id = CONCAT(@first_word, '-', @remaining_words, '-', LPAD(company_count + 1, 4, '0'));
        //         ELSE
        //             -- Single word, use the entire word as emp_id

        //             -- Check if the company is new or already exists
        //             SELECT COUNT(*) INTO company_count FROM employee_details WHERE company_name = NEW.company_name;

        //             IF company_count > 0 THEN
        //                 -- Existing company, increment the counter
        //                 SELECT MAX(SUBSTRING_INDEX(emp_id, '-', -1)) INTO company_count FROM employee_details WHERE company_name = NEW.company_name;
        //                 SET NEW.emp_id = CONCAT(UPPER(NEW.company_name), '-', LPAD(company_count + 1, 4, '0'));
        //             ELSE
        //                 -- New company, start the counter from 0001
        //                 SET NEW.emp_id = CONCAT(UPPER(NEW.company_name), '-0001');
        //             END IF;
        //         END IF;
        //     ELSE
        //         -- Set the emp_id to null if the company name is empty
        //         SET NEW.emp_id = NULL;
        //     END IF;
        // END;
        // SQL;

        //  DB::unprepared($triggerSQL);



        // Add a unique constraint for mobile_number and alternate_mobile_number
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $triggerSQL = <<<SQL
        DROP TRIGGER IF EXISTS generate_emp_id;
        SQL;
        DB::unprepared($triggerSQL);
        Schema::dropIfExists('employee_details');
    }
};
