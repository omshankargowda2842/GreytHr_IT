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
            $table->id();
            $table->string('vendor_id')->nullable()->default(null)->unique();
            $table->string('vendor_name')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('gst')->unique()->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('branch')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pin_code')->nullable();
            $table->text('note_description')->nullable();
            $table->json('file_paths')->nullable();
            $table->string('delete_vendor_reason')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        $triggerSQL = <<<SQL
        CREATE TRIGGER generate_vendor_id BEFORE INSERT ON vendors
        FOR EACH ROW
        BEGIN
            -- Check if vendor_id is NULL
            IF NEW.vendor_id IS NULL THEN
                -- Find the maximum vendor_id value in the vendors table
                SET @max_id := IFNULL((SELECT MAX(CAST(SUBSTRING(vendor_id, 5) AS UNSIGNED)) + 1 FROM vendors), 10000);

                -- Increment the max_id and assign it to the new vendor_id
                SET NEW.vendor_id = CONCAT('VEN-', LPAD(@max_id, 5, '0'));
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
        Schema::dropIfExists('vendors');
    }
};
