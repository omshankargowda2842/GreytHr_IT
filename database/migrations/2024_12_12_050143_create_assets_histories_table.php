<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assets_histories', function (Blueprint $table) {
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
            $table->text('barcode')->nullable();
            $table->string('invoice_number', 100)->nullable();
            $table->decimal('taxable_amount', 10, 2)->nullable();
            $table->decimal('invoice_amount', 10, 2)->nullable();
            $table->string('gst_ig', 50)->nullable();
            $table->string('gst_state', 50)->nullable();
            $table->string('gst_central', 50)->nullable();
            $table->string('status', 50)->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('warranty_expire_date')->nullable();
            $table->string('end_of_life', 30)->nullable();
            $table->json('file_paths')->nullable();
            $table->string('delete_asset_reason', 200)->nullable();
            $table->boolean('is_active');
            $table->string('assign_or_un_assign', 30);
            $table->string('created_by', 30);
            $table->string('action', 10);
            $table->foreign('asset_id')
                ->references('asset_id')
                ->on('vendor_assets')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets_histories');
    }
};
