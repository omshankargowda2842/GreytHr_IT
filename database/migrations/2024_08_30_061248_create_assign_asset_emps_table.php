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
        Schema::create('assign_asset_emps', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('emp_id',10)->nullable();
            $table->string('asset_id',10)->nullable();
            $table->string('manufacturer',100)->nullable();
            $table->integer('asset_type')->nullable();
            $table->string('employee_name',100)->nullable();
            $table->string('department',100)->nullable();
            $table->string('delete_reason',100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('assign_asset_emps');
    }
};
