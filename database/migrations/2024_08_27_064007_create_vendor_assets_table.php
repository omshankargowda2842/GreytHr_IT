<?php

namespace App\Models;

use App\Models\Vendor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $primaryKey = 'asset_id';
    public function up(): void
    {
        Schema::create('vendor_assets', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('vendor_id', 10)->nullable();
            $table->string('asset_id', 10)->unique()->nullable();
            $table->string('manufacturer', 100)->nullable();
            $table->integer('asset_type')->nullable();
            $table->string('asset_model', 100)->nullable();
            $table->text('asset_specification')->nullable();
            $table->string('color', 50)->nullable();
            $table->string('version', 100)->nullable();
            $table->string('serial_number', 50)->nullable();
            $table->string('barcode', 100)->nullable();
            $table->string('invoice_number', 100)->nullable();
            $table->decimal('taxable_amount', 10, 2)->nullable();
            $table->decimal('invoice_amount', 10, 2)->nullable();
            $table->string('gst_state', 50)->nullable();
            $table->string('gst_central', 50)->nullable();
            $table->date('purchase_date')->nullable();
            $table->json('file_paths')->nullable();
            $table->string('delete_asset_reason', 200)->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreign('vendor_id')
                ->references('vendor_id')
                ->on('vendors')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->timestamps();
        });

        $triggerSQL = <<<SQL
        CREATE TRIGGER generate_asset_id BEFORE INSERT ON vendor_assets
        FOR EACH ROW
        BEGIN
            -- Check if asset_id is NULL
            IF NEW.asset_id IS NULL THEN
                -- Find the maximum asset_id value in the vendor_assets table
                SET @max_id := IFNULL((SELECT MAX(CAST(SUBSTRING(asset_id, 5) AS UNSIGNED)) + 1 FROM vendor_assets), 10000);

                -- Increment the max_id and assign it to the new asset_id
                SET NEW.asset_id = CONCAT('ASS-', LPAD(@max_id , 5, '0'));
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
        Schema::dropIfExists('vendor_assets');
    }
};
