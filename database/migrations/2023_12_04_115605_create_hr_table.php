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
            $table->string('hr_emp_id')->nullable()->default(null)->unique();
            $table->string('company_id');
            $table->string('employee_name');
            $table->string('image');
            $table->string('position');
            $table->string('department');
            $table->string('email')->unique();
            $table->string('company_email');
            $table->string('phone_number');
            $table->string('emergency_contact_number');
            $table->date('date_of_birth');
            $table->string('address');
            $table->string('nationality');
            $table->string('marital_status');
            $table->string('gender');
            $table->string('tax_id');
            $table->string('password');
            $table->decimal('salary', 10, 2);
            $table->date('joining_date');
            $table->boolean('is_active')->default(true);
            $table->foreign('company_id')
                ->references('company_id') // Assuming the primary key of the companies table is 'id'
                ->on('companies')
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
