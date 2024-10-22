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
        Schema::create('emp_sub_departments', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('sub_dept_id', 10)->unique();
            $table->string('sub_department', 100);
            $table->string('dept_id', 10);
            $table->foreign('dept_id')->references('dept_id')->on('emp_departments')->onDelete('cascade');
            $table->timestamps();
        });

        // Create the trigger for auto-generating sub_dept_id
        DB::statement('
            CREATE TRIGGER auto_generate_sub_dept_id
            BEFORE INSERT ON emp_sub_departments
            FOR EACH ROW
            BEGIN
                DECLARE max_id INT;
                DECLARE prefix VARCHAR(2);
                DECLARE generated_id VARCHAR(6);

                -- Set prefix for sub_dept_id
                SET prefix = "99";

                -- Check if sub_dept_id is set or not
                IF NEW.sub_dept_id IS NULL OR NEW.sub_dept_id = "" THEN
                    -- Get the maximum sub_dept_id from the table and extract the numeric part
                    SELECT COALESCE(MAX(CAST(SUBSTRING(sub_dept_id COLLATE utf8mb4_unicode_ci, LENGTH(prefix) + 1) AS UNSIGNED)), 0) INTO max_id
                    FROM emp_sub_departments
                    WHERE sub_dept_id COLLATE utf8mb4_unicode_ci LIKE CONCAT(prefix, "%");

                    -- Generate the new sub_dept_id with a 2-digit number
                    SET generated_id = CONCAT(prefix, LPAD(max_id + 1, 2, "0"));

                    -- Assign the generated_id to NEW.sub_dept_id
                    SET NEW.sub_dept_id = generated_id;
                ELSE
                    -- Check if the provided sub_dept_id already exists
                    IF EXISTS (SELECT 1 FROM emp_sub_departments WHERE sub_dept_id COLLATE utf8mb4_unicode_ci = NEW.sub_dept_id) THEN
                        SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "sub_dept_id already exists.";
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
        DB::statement('DROP TRIGGER IF EXISTS auto_generate_sub_dept_id');

        // Drop the emp_sub_departments table
        Schema::dropIfExists('emp_sub_departments');
    }
};
