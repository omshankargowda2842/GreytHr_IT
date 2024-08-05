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
        Schema::create('employee_assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_id')->nullable()->default(null)->unique();
            $table->string('asset_tag')->nullable();
            $table->string('status')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('asset_type')->nullable();
            $table->string('asset_model')->nullable();
            $table->text('asset_specification')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('color')->nullable();
            $table->string('current_owner')->nullable();
            $table->string('previous_owner')->nullable();
            $table->string('windows_version')->nullable();
            $table->date('assign_date')->nullable();
            $table->date('purchase_date')->nullable();
            $table->string('invoice_no')->nullable();
            $table->decimal('taxable_amount', 8, 2)->nullable();
            $table->decimal('gst_central', 8, 2)->nullable();
            $table->decimal('gst_state', 8, 2)->nullable();
            $table->decimal('invoice_amount', 8, 2)->nullable();
            $table->string('vendor')->nullable();
            $table->text('other_assets')->nullable();
            $table->enum('sophos_antivirus', ['Yes', 'No'])->default('No');
            $table->enum('vpn_creation', ['Yes', 'No'])->default('No');
            $table->enum('teramind', ['Yes', 'No'])->default('No');
            $table->string('system_name')->nullable();
            $table->enum('system_upgradation', ['Yes', 'No'])->default('No');
            $table->text('screenshot_of_programms')->nullable();
            $table->enum('one_drive', ['Yes', 'No'])->default('No');
            $table->string('mac_address')->nullable();
            $table->enum('laptop_received', ['Yes', 'No'])->default('No');
            $table->date('laptop_received_date')->nullable();
            $table->timestamps();
        });

        $triggerSQL = <<<SQL
        CREATE TRIGGER asset_id BEFORE INSERT ON employee_assets FOR EACH ROW
        BEGIN
            -- Check if bill_number is NULL
            IF NEW.asset_id IS NULL THEN
                -- Find the maximum bill_number value in the bills table
                SET @max_id := IFNULL((SELECT MAX(CAST(SUBSTRING(asset_id, 4) AS UNSIGNED)) FROM employee_assets), 0);

                -- Increment the max_id and assign it to the new bill_number
                SET NEW.asset_id = CONCAT('AST', LPAD(@max_id + 1, 6, '0'));
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
        Schema::dropIfExists('employee_assets');
    }
};
