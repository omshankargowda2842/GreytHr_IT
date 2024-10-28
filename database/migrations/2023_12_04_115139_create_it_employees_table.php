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
        Schema::create('it_employees', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('it_emp_id', 10)->nullable()->unique();
            $table->string('emp_id', 10);
            $table->string('email', 100)->unique();
            $table->string('employee_name', 100)->nullable();
              $table->string('delete_itmember_reason', 10)->nullable();
            $table->string('password')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->enum('role', ['user', 'admin', 'super_admin'])->default('user');

            $table->foreign('emp_id')
                ->references('emp_id')
                ->on('employee_details')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->timestamps();
        });

        // Trigger to auto-generate it_emp_id
        $triggerSQL = <<<SQL
        CREATE TRIGGER generate_it_emp_id BEFORE INSERT ON it_employees FOR EACH ROW
        BEGIN
            IF NEW.it_emp_id IS NULL THEN
                -- Generate it-10000 style ID
                SET @max_id := IFNULL(
                    (SELECT MAX(CAST(SUBSTRING(it_emp_id, 4) AS UNSIGNED)) FROM it_employees),
                    9999
                ) + 1;

                SET NEW.it_emp_id = CONCAT('IT-', LPAD(@max_id, 5, '0'));
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
        // Drop the trigger first
        DB::unprepared('DROP TRIGGER IF EXISTS generate_it_emp_id');

        // Drop the 'it' table
        Schema::dropIfExists('it_employees');
    }
};
