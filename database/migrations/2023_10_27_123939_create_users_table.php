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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->unique();
            $table->enum('user_type', ['Job Seeker', 'Vendor']);
            $table->string('company_id')->unique()->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_logo')->nullable();
            $table->binary('image')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('experience_status')->nullable();
            $table->string('available_to_join')->nullable();
            $table->string('profile_summary')->nullable();
            $table->json('technical_skills')->nullable();
            $table->json('education')->nullable();
            $table->string('current_industry')->nullable();
            $table->string('role_category')->nullable();
            $table->string('desired_job_type')->nullable();
            $table->string('preferred_shift')->nullable();
            $table->string('expected_salary')->nullable();
            $table->string('department')->nullable();
            $table->string('job_role')->nullable();
            $table->string('desired_employment_type')->nullable();
            $table->string('preferred_work_location')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('religion')->nullable();
            $table->string('differently_abled')->nullable();
            $table->string('career_break')->nullable();
            $table->json('languages')->nullable();

            $table->string('full_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('mobile_no')->unique()->nullable();
            $table->string('work_status')->nullable();
            $table->string('address')->nullable();
            $table->string('resume')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // You cannot use the DELIMITER command in Laravel migrations.
        // Instead, you can use raw SQL to create the trigger.

        $triggerSQL = <<<SQL
        CREATE TRIGGER generate_user_id BEFORE INSERT ON users FOR EACH ROW
        BEGIN
            DECLARE max_id INT;

            -- Find the maximum user_id value in the users table
            SELECT IFNULL(MAX(CAST(SUBSTRING(user_id, 4) AS UNSIGNED)) + 1, 1) INTO max_id FROM users;

            -- Increment the max_id and assign it to the new user_id
            SET NEW.user_id = CONCAT("USR", LPAD(max_id, 4, "0"));
        END;
        SQL;

        DB::unprepared($triggerSQL);

      // You cannot use the DELIMITER command in Laravel migrations.
        // Instead, you can use raw SQL to create the trigger.

        $triggerSQLCompany = <<<SQL
        CREATE TRIGGER generate_company_id BEFORE INSERT ON users FOR EACH ROW
        BEGIN
            DECLARE company_count INT;

            IF TRIM(IFNULL(NEW.company_name, '')) != '' THEN
                -- Check if the company name has more than one word
                IF LOCATE(' ', NEW.company_name) > 0 THEN
                    -- More than one word, take the first word and use prefixes for the remaining words
                    SET @first_word = UPPER(SUBSTRING_INDEX(NEW.company_name, ' ', 1));
                    SET @remaining_words = UPPER(SUBSTRING(SUBSTRING_INDEX(NEW.company_name, ' ', -1), 1, 3));

                    -- Count the number of existing users with the same company name
                    SELECT COUNT(*) INTO company_count FROM users WHERE company_name = NEW.company_name;

                    -- Generate the unique company_id using the first word, remaining words, and an auto-incremented value
                    SET NEW.company_id = CONCAT('V-', @first_word, '-', @remaining_words, '-', LPAD(company_count + 1, 4, '0'));
                ELSE
                    -- Single word, use the entire word as company_id

                    -- Check if the company is new or already exists
                    SELECT COUNT(*) INTO company_count FROM users WHERE company_name = NEW.company_name;

                    IF company_count > 0 THEN
                        -- Existing company, increment the counter
                        SELECT MAX(SUBSTRING_INDEX(company_id, '-', -1)) INTO company_count FROM users WHERE company_name = NEW.company_name;
                        SET NEW.company_id = CONCAT('V-', UPPER(NEW.company_name), '-', LPAD(company_count + 1, 4, '0'));
                    ELSE
                        -- New company, start the counter from 0001
                        SET NEW.company_id = CONCAT('V-', UPPER(NEW.company_name), '-0001');
                    END IF;
                END IF;
            ELSE
                -- Set the company_id to null if the company name is empty
                SET NEW.company_id = NULL;
            END IF;
        END;
        SQL;

        DB::unprepared($triggerSQLCompany);

    }
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
