<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('emp_departments', function (Blueprint $table) {
            $table->id();
            $table->string('dept_id')->unique();
            $table->string('department');
            $table->string('company_id')->nullable();
            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('cascade');
            $table->timestamps();
        });

        // Create the trigger for auto-generating dept_id
        DB::statement('
            CREATE TRIGGER auto_generate_dept_id
            BEFORE INSERT ON emp_departments
            FOR EACH ROW
            BEGIN
                DECLARE max_id INT;
                DECLARE prefix VARCHAR(2);
                DECLARE generated_id VARCHAR(6);

                -- Set prefix for dept_id
                SET prefix = "88";

                -- Check if dept_id is set or not
                IF NEW.dept_id IS NULL OR NEW.dept_id = "" THEN
                    -- Get the maximum dept_id from the table and extract the numeric part
                    SELECT COALESCE(MAX(CAST(SUBSTRING(dept_id COLLATE utf8mb4_unicode_ci, LENGTH(prefix) + 1) AS UNSIGNED)), 0) INTO max_id
                    FROM emp_departments
                    WHERE dept_id COLLATE utf8mb4_unicode_ci LIKE CONCAT(prefix, "%");

                    -- Generate the new dept_id with a 2-digit number
                    SET generated_id = CONCAT(prefix, LPAD(max_id + 1, 2, "0"));

                    -- Assign the generated_id to NEW.dept_id
                    SET NEW.dept_id = generated_id;
                ELSE
                    -- Check if the provided dept_id already exists
                    IF EXISTS (SELECT 1 FROM emp_departments WHERE dept_id COLLATE utf8mb4_unicode_ci = NEW.dept_id) THEN
                        SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "dept_id already exists.";
                    END IF;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Drop the trigger
        DB::statement('DROP TRIGGER IF EXISTS auto_generate_dept_id');

        // Drop the emp_departments table
        Schema::dropIfExists('emp_departments');
    }
};
