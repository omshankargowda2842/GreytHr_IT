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
        Schema::create('jobs', function (Blueprint $table) {
            $table->string('job_id', 10)->primary();
            $table->string('contact_email', 100);
            $table->string('title', 100);
            $table->text('description');
            $table->string('location', 50);
            $table->decimal('salary', 10, 2);
            $table->string('company_id', 10);
            $table->string('company_name', 100);
            $table->string('hr_name', 100);
            $table->date('expire_date');
            $table->string('contact_phone', 20);
            $table->integer('vacancies');
            $table->string('education_requirement');
            $table->string('experience_requirement');
            $table->string('skills_required');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            // Additional Fields
            $table->string('application_link')->nullable(); // Link to the application page
            $table->string('job_type', 20)->default('Full-time');
            $table->text('responsibilities')->nullable(); // Job responsibilities
            $table->text('benefits')->nullable(); // Job benefits
            $table->text('application_instructions')->nullable();
            $table->foreign('company_id')
                ->references('company_id')
                ->on('companies')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            // Instructions for applying
        });

        $triggerSQL = <<<SQL
        CREATE TRIGGER generate_job_id BEFORE INSERT ON jobs FOR EACH ROW
        BEGIN
            DECLARE max_id INT;

            -- Find the maximum job_id value in the jobs table
            SELECT IFNULL(MAX(CAST(SUBSTRING(job_id, 4) AS UNSIGNED)) + 1, 1) INTO max_id FROM jobs;

            -- Increment the max_id and assign it to the new job_id
            SET NEW.job_id = CONCAT("JOB", LPAD(max_id, 4, "0"));
        END;
        SQL;

        DB::unprepared($triggerSQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
