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
        Schema::create('posts', function (Blueprint $table) {
            $table->smallInteger('id')->autoIncrement();
            $table->string('hr_emp_id', 10)->nullable();

            $table->string('admin_emp_id', 10)->nullable();
            $table->string('manager_id', 10)->nullable();
            $table->string('emp_id', 10)->nullable();
            $table->enum('category',  ['Appreciations',  'Companynews', 'Events', 'Everyone', 'Hyderabad', 'US']);
            $table->text('description');
            $table->binary('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('status')->default('Open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
