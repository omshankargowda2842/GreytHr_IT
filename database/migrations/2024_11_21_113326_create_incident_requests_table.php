<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('incident_requests', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('snow_id')->nullable()->unique(); // Auto-generated Incident/Service Request ID
            $table->string('emp_id', 10);
            $table->string('category'); // 'Incident Request' or 'Service Request'
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->enum('priority', ['Low', 'Medium', 'High'])->default('Low');
            $table->string('assigned_dept')->nullable();
            $table->json('file_paths')->nullable();
            $table->json('it_file_paths')->nullable();
            $table->string('inc_assign_to')->nullable();
            $table->text('active_inc_notes')->nullable();
            $table->text('inc_pending_notes')->nullable();
            $table->timestamp('in_progress_since')->nullable();
            $table->text('inc_inprogress_notes')->nullable();
            $table->text('inc_completed_notes')->nullable();
            $table->text('ser_completed_notes')->nullable();
            $table->text('inc_cancel_notes')->nullable();
            $table->text('ser_cancel_notes')->nullable();
            $table->text('inc_customer_visible_notes')->nullable();
            $table->text('ser_customer_visible_notes')->nullable();
            $table->integer('total_in_progress_time')->default(0);
            $table->timestamp('inc_end_date')->nullable();
            $table->timestamp('ser_end_date')->nullable();
            $table->string('ser_assign_to')->nullable();
            $table->text('active_ser_notes')->nullable();
            $table->text('ser_pending_notes')->nullable();
            $table->text('ser_inprogress_notes')->nullable();
            $table->integer('total_ser_progress_time')->default(0);
            $table->timestamp('ser_progress_since')->nullable();
            $table->tinyInteger('status_code')->default(11); // Default to a "Pending" status
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('emp_id')
                ->references('emp_id')
                ->on('employee_details')
                ->onDelete('restrict');
            $table->foreign('status_code')
                ->references('status_code')
                ->on('status_types')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });

        // Create the trigger for auto-generating snow_id
        $triggerSQL = <<<SQL
        CREATE TRIGGER generate_snow_id BEFORE INSERT ON incident_requests FOR EACH ROW
        BEGIN
            IF NEW.snow_id IS NULL THEN
                IF NEW.category = 'Incident Request' THEN
                    SET @max_id := IFNULL(
                        (SELECT MAX(CAST(SUBSTRING(snow_id, 5) AS UNSIGNED))
                         FROM incident_requests WHERE snow_id LIKE 'INC-%'),
                        0
                    ) + 1;

                    SET NEW.snow_id = CONCAT('INC-', LPAD(@max_id, 4, '0'));
                ELSEIF NEW.category = 'Service Request' THEN
                    SET @max_id := IFNULL(
                        (SELECT MAX(CAST(SUBSTRING(snow_id, 5) AS UNSIGNED))
                         FROM incident_requests WHERE snow_id LIKE 'SER-%'),
                        0
                    ) + 1;

                    SET NEW.snow_id = CONCAT('SER-', LPAD(@max_id, 4, '0'));
                END IF;
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
        // Drop the trigger
        DB::unprepared('DROP TRIGGER IF EXISTS generate_snow_id');

        // Drop the table
        Schema::dropIfExists('incident_requests');
    }
};
