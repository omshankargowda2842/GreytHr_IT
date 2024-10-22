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
        Schema::create('company_projects', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('company_id',10);
            $table->string('project_name',100);
            $table->string('client_name',100)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->tinyInteger('project_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
