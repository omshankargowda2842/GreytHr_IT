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
            $table->id();
            $table->string('it_emp_id')->nullable()->default(null)->unique();
            $table->string('employee_name');
            $table->string('image');
            $table->string('company_id');
            $table->string('designation');
            $table->string('skills');
            $table->integer('experience_years');
            $table->string('education');
            $table->string('certifications');
            $table->date('date_of_birth');
            $table->string('address');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->string('company_email');
            $table->text('bio');
            $table->string('linkedin_profile')->nullable();
            $table->string('github_profile')->nullable();
            $table->decimal('salary', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->string('password');

            $table->foreign('company_id')
                ->references('company_id') // Assuming the primary key of the companies table is 'id'
                ->on('companies')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->timestamps();
        });
        $triggerSQL = <<<SQL
        CREATE TRIGGER generate_it_emp_id BEFORE INSERT ON i_t FOR EACH ROW
        BEGIN
            -- Check if bill_number is NULL
            IF NEW.it_emp_id IS NULL THEN
                -- Find the maximum bill_number value in the bills table
                SET @max_id := IFNULL((SELECT MAX(CAST(SUBSTRING(it_emp_id, 3) AS UNSIGNED)) + 1 FROM i_t), 100000);

                -- Increment the max_id and assign it to the new bill_number
                SET NEW.it_emp_id = CONCAT('IT', LPAD(@max_id, 6, '0'));
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
        Schema::dropIfExists('_i_t');
    }
};
