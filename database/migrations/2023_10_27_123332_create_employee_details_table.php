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
            $table->date('date_of_birth');
            $table->string('gender');
            $table->string('email')->unique();
            $table->string('company_email');
            $table->string('company_name');
            $table->string('mobile_number')->unique();
            $table->string('alternate_mobile_number')->unique();
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('postal_code');
            $table->string('country');
            $table->date('hire_date');
            $table->enum('employee_type', ['full-time', 'part-time', 'contract']);
            $table->string('department');
            $table->string('job_title');
            $table->string('manager_id')->nullable();
            $table->string('report_to')->nullable();
            $table->enum('employee_status', ['active', 'on-leave', 'terminated'])->default('active');
            $table->string('emergency_contact')->nullable();
            $table->string('password')->nullable();
            $table->string('image');
            $table->string('blood_group');
            $table->string('nationality');
            $table->string('religion');
            $table->tinyInteger('status')->default(1);
            $table->enum('marital_status', ['married', 'unmarried']);
            $table->string('spouse')->nullable();
            $table->enum('physically_challenge', ['yes', 'no']);
            $table->enum('inter_emp', ['yes', 'no']);
            $table->string('job_location');
            $table->string('education');
            $table->string('experience')->nullable();
            $table->string('pan_no')->unique()->nullable();
            $table->string('adhar_no')->unique()->nullable();
            $table->string('pf_no')->unique()->nullable();
            $table->string('nick_name')->nullable();
            $table->string('time_zone')->nullable();
            $table->string('biography')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linked_in')->nullable();
            $table->string('is_starred')->nullable();
            $table->string('company_id');
            $table->foreign('company_id')
                ->references('company_id') // Assuming the primary key of the companies table is 'id'
                ->on('companies')
                ->onDelete('restrict')
                ->onUpdate('cascade');


            $table->timestamps();
        });

        $triggerSQL = <<<SQL
        CREATE TRIGGER generate_emp_id BEFORE INSERT ON employee_details FOR EACH ROW
        BEGIN
            DECLARE company_count INT;

            IF TRIM(IFNULL(NEW.company_name, '')) != '' THEN
                -- Check if the company name has more than one word
                IF LOCATE(' ', NEW.company_name) > 0 THEN
                    -- More than one word, take the first word and use prefixes for the remaining words
                    SET @first_word = UPPER(SUBSTRING_INDEX(NEW.company_name, ' ', 1));
                    SET @remaining_words = UPPER(SUBSTRING(SUBSTRING_INDEX(NEW.company_name, ' ', -1), 1, 3));

                    -- Count the number of existing employees with the same company name
                    SELECT COUNT(*) INTO company_count FROM employee_details WHERE company_name = NEW.company_name;
                    SET NEW.emp_id = CONCAT(@first_word, '-', @remaining_words, '-', LPAD(company_count + 1, 4, '0'));
                ELSE
                    -- Single word, use the entire word as emp_id

                    -- Check if the company is new or already exists
                    SELECT COUNT(*) INTO company_count FROM employee_details WHERE company_name = NEW.company_name;

                    IF company_count > 0 THEN
                        -- Existing company, increment the counter
                        SELECT MAX(SUBSTRING_INDEX(emp_id, '-', -1)) INTO company_count FROM employee_details WHERE company_name = NEW.company_name;
                        SET NEW.emp_id = CONCAT(UPPER(NEW.company_name), '-', LPAD(company_count + 1, 4, '0'));
                    ELSE
                        -- New company, start the counter from 0001
                        SET NEW.emp_id = CONCAT(UPPER(NEW.company_name), '-0001');
                    END IF;
                END IF;
            ELSE
                -- Set the emp_id to null if the company name is empty
                SET NEW.emp_id = NULL;
            END IF;
        END;
        SQL;

         DB::unprepared($triggerSQL);



        // Add a unique constraint for mobile_number and alternate_mobile_number
        DB::unprepared('ALTER TABLE employee_details ADD CONSTRAINT unique_mobile_numbers UNIQUE (mobile_number, alternate_mobile_number)');
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
