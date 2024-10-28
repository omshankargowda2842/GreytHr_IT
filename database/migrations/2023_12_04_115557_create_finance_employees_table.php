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
        Schema::create('finance_employees', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('fi_emp_id', 10)->nullable()->unique();
            $table->string('emp_id');
            $table->string('employee_name', 100);
            $table->string('email', 100)->unique();
            $table->string('password')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->enum('role', ['user', 'admin', 'super_admin'])->default('user'); // Define ENUM for roles
            $table->foreign('emp_id')
                ->references('emp_id')
                ->on('employee_details')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->timestamps();
        });

        $triggerSQL = <<<SQL
        CREATE TRIGGER generate_fi_emp_id BEFORE INSERT ON finance_employees FOR EACH ROW
        BEGIN
            -- Check if bill_number is NULL
            IF NEW.fi_emp_id IS NULL THEN
                -- Find the maximum bill_number value in the bills table
                SET @max_id := IFNULL((SELECT MAX(CAST(SUBSTRING(fi_emp_id, 3) AS UNSIGNED)) + 1 FROM finance_employees), 10000);

                -- Increment the max_id and assign it to the new bill_number
                SET NEW.fi_emp_id = CONCAT('FI-', LPAD(@max_id, 5, '0'));
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
        DB::unprepared('DROP TRIGGER IF EXISTS generate_fi_emp_id');
        Schema::dropIfExists('finance_employees');
    }
};
