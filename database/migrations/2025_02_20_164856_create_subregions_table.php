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
        Schema::create('subregions', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('name', 100);
            $table->text('translations')->nullable();
            $table->mediumInteger('region_id')->unsigned();
            $table->boolean('flag')->default(1);
            $table->string('wikiDataId')->nullable();
            $table->timestamps();
            // Foreign key constraint
            $table->foreign('region_id')->references('id')->on('regions');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subregions');
    }
};
