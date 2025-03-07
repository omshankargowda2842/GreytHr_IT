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
        Schema::create('assign_asset_emps', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id',10)->nullable();
            $table->string('asset_id',10)->nullable();
            $table->string('manufacturer',100)->nullable();
            $table->integer('asset_type')->nullable();
            $table->string('employee_name',100)->nullable();
            $table->string('department',100)->nullable();
            $table->json('asset_assign_file_path')->nullable();
            $table->json('asset_deactivate_file_path')->nullable();
            $table->string('system_name',100)->nullable();
            $table->string('sophos_antivirus',10)->nullable();
            $table->string('vpn_creation',10)->nullable();
            $table->string('teramind',10)->nullable();
            $table->string('system_upgradation',10)->nullable();
            $table->string('one_drive',10)->nullable();
            $table->string('screenshot_programs',10)->nullable();
            $table->string('mac_address',100)->nullable();
            $table->date('assigned_at')->nullable();
            $table->date('laptop_received')->nullable();
            $table->string('delete_reason',200)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('deleted_at')->nullable();
            $table->foreign('asset_id')
                ->references('asset_id')
                ->on('vendor_assets')
                ->onDelete('restrict')
                ->onUpdate('cascade');
            $table->timestamps();
        });
        DB::unprepared('
        CREATE TRIGGER generate_system_name BEFORE INSERT ON assign_asset_emps
        FOR EACH ROW
        BEGIN
            DECLARE existing_system_name VARCHAR(100);
            DECLARE latest_number INT;
            DECLARE new_number INT;

            -- Check if asset_id exists in the table with a non-NULL system_name
            SELECT system_name INTO existing_system_name
            FROM assign_asset_emps
            WHERE asset_id = NEW.asset_id AND system_name IS NOT NULL
            LIMIT 1;

            -- If asset_id already has a system_name, use the existing one
            IF existing_system_name IS NOT NULL THEN
                SET NEW.system_name = existing_system_name;
            ELSE
                -- Get the latest system_name number
                SELECT IFNULL(MAX(CAST(SUBSTRING(system_name, LENGTH("XSSPAYG-LAP-") + 1) AS UNSIGNED)), 0) INTO latest_number
                FROM assign_asset_emps
                WHERE system_name LIKE "XSSPAYG-LAP-%";

                -- Increment the number
                SET new_number = latest_number + 1;

                -- Generate the new system_name
                SET NEW.system_name = CONCAT("XSSPAYG-LAP-", LPAD(new_number, 3, "0"));
            END IF;
        END;
        ');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assign_asset_emps');
    }
};
