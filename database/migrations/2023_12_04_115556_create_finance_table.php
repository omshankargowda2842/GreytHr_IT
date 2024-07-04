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
        Schema::create('finance', function (Blueprint $table) {
            $table->id();
            $table->string('fi_emp_id')->nullable()->default(null)->unique();
            $table->string('company_id');
            $table->string('employee_name');
            $table->string('image');
            $table->date('date_of_birth');
            $table->string('role');
            $table->decimal('salary', 10, 2);
            $table->date('hire_date');
            $table->string('department');
            $table->string('work_location');
            $table->string('manager_name');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('routing_number');
            $table->string('beneficiary_name');
            $table->string('beneficiary_relationship');
            $table->string('emergency_contact_number');
            $table->text('address');
            $table->string('password');

            $table->string('phone_number');
            $table->string('email')->unique();
            $table->string('company_email');
            $table->boolean('is_active')->default(true);
            $table->foreign('company_id')
                ->references('company_id') // Assuming the primary key of the companies table is 'id'
                ->on('companies')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->timestamps();
        });

        $triggerSQL = <<<SQL
        CREATE TRIGGER generate_fi_emp_id BEFORE INSERT ON finance FOR EACH ROW
        BEGIN
            -- Check if bill_number is NULL
            IF NEW.fi_emp_id IS NULL THEN
                -- Find the maximum bill_number value in the bills table
                SET @max_id := IFNULL((SELECT MAX(CAST(SUBSTRING(fi_emp_id, 3) AS UNSIGNED)) + 1 FROM finance), 100000);

                -- Increment the max_id and assign it to the new bill_number
                SET NEW.fi_emp_id = CONCAT('FI', LPAD(@max_id, 6, '0'));
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
        Schema::dropIfExists('finance');
    }
};
