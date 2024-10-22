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
        Schema::create('i_t', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('it_emp_id', 10)->nullable()->unique(); // Increased to 15 characters
            $table->string('employee_name', 100);
            $table->string('emp_id', 10);
            $table->string('email', 100)->unique();
            $table->string('delete_itmember_reason', 10)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->enum('role', ['user', 'admin', 'super_admin'])->default('user'); // Define ENUM for roles
            $table->string('password')->nullable();
            $table->timestamps();

            $table->foreign('emp_id')
                ->references('emp_id')
                ->on('employee_details')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });

        // Creating a trigger to auto-generate it_emp_id in the format IT-10000, IT-10001, etc.
        $triggerSQL = <<<SQL
        CREATE TRIGGER generate_it_emp_id BEFORE INSERT ON i_t FOR EACH ROW
        BEGIN
            IF NEW.it_emp_id IS NULL THEN
                -- Fetch the maximum numeric value from it_emp_id
                SET @max_id := IFNULL(
                    (SELECT MAX(CAST(SUBSTRING(it_emp_id, 3) AS UNSIGNED)) FROM i_t),
                    9999
                );

                -- Increment and assign the new it_emp_id
                SET NEW.it_emp_id = CONCAT('IT-', LPAD(@max_id + 1, 5, '0'));
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
        // Drop the trigger first before dropping the table
        DB::unprepared('DROP TRIGGER IF EXISTS generate_it_emp_id');
        Schema::dropIfExists('i_t');
    }
};
