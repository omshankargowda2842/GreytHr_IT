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
        Schema::create('vendors', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('vendor_id',10)->unique()->nullable();
            $table->string('vendor_name',100)->nullable();
            $table->string('contact_name',100)->nullable();
            $table->string('phone',20)->nullable();
            $table->string('gst',50)->unique()->nullable();
            $table->string('bank_name',50)->nullable();
            $table->string('account_number',30)->nullable();
            $table->string('ifsc_code',30)->nullable();
            $table->string('branch',100)->nullable();
            $table->string('contact_email',100)->nullable();
            $table->string('street',100)->nullable();
            $table->string('city',100)->nullable();
            $table->string('state',50)->nullable();
            $table->string('pin_code',6 )->nullable();
            $table->text('description')->nullable();
            $table->json('file_paths')->nullable();
            $table->string('delete_vendor_reason',200)->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        // Define the trigger
        $triggerSQL = <<<SQL
        CREATE TRIGGER generate_vendor_id
        BEFORE INSERT ON vendors
        FOR EACH ROW
        BEGIN
            -- Check if vendor_id is NULL
            IF NEW.vendor_id IS NULL THEN
                -- Find the maximum vendor_id value in the vendors table
                SET @max_id := IFNULL((SELECT MAX(CAST(SUBSTRING(vendor_id, 3) AS UNSIGNED)) FROM vendors), 9999);

                -- Increment the max_id and assign it to the new vendor_id
                SET NEW.vendor_id = CONCAT('V-', LPAD(@max_id + 1, 5, '0'));
            END IF;
        END;
        SQL;

        // Execute the trigger SQL
        DB::unprepared($triggerSQL);
    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS generate_vendor_id;');
        Schema::dropIfExists('vendors');
    }
};
