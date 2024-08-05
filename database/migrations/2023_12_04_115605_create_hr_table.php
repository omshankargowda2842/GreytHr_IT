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
        Schema::create('hr', function (Blueprint $table) {
            $table->id();
            $table->string('hr_emp_id')->unique()->nullable();
            $table->string('emp_id');
            $table->string('employee_name')->nullable();
            $table->binary('image')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('emergency_contact_number')->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('phone_number')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->foreign('emp_id')
                ->references('emp_id')
                ->on('employee_details')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->timestamps();
        });


        $triggerSQL = <<<SQL
        CREATE TRIGGER generate_hr_emp_id BEFORE INSERT ON hr FOR EACH ROW
        BEGIN
            -- Check if bill_number is NULL
            IF NEW.hr_emp_id IS NULL THEN
                -- Find the maximum bill_number value in the bills table
                SET @max_id := IFNULL((SELECT MAX(CAST(SUBSTRING(hr_emp_id, 3) AS UNSIGNED)) + 1 FROM hr), 100000);

                -- Increment the max_id and assign it to the new bill_number
                SET NEW.hr_emp_id = CONCAT('HR', LPAD(@max_id, 6, '0'));
            END IF;
        END;
    SQL;

        DB::unprepared($triggerSQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr');
    }
};
