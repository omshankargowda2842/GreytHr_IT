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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chating_id')->constrained()->cascadeOnDelete();

            $table->string('sender_id')->nullable();// or uuid()
            $table->foreign('sender_id')->references('emp_id')->on('employee_details')->nullOnDelete();

            $table->string('receiver_id')->nullable();// or uuid()
            $table->foreign('receiver_id')->references('emp_id')->on('employee_details')->nullOnDelete();


            $table->timestamp('read_at')->nullable();

            //delete actions
            $table->timestamp('receiver_deleted_at')->nullable();
            $table->timestamp('sender_deleted_at')->nullable();
            $table->string('file_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->binary('file_path')->nullable();
            $table->text('body')->nullable();
            $table->timestamps();
        });
        // Schema::create('messages', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('chating_id')->constrained()->cascadeOnDelete();

        //     $table->string('sender_id')->nullable();// or uuid()
        //     $table->foreign('sender_id')->references('emp_id')->on('employee_details')->nullOnDelete();

        //     $table->string('receiver_id')->nullable();// or uuid()
        //     $table->foreign('receiver_id')->references('it_emp_id')->on('employee_details')->nullOnDelete();


        //     $table->timestamp('read_at')->nullable();

        //     //delete actions
        //     $table->timestamp('receiver_deleted_at')->nullable();
        //     $table->timestamp('sender_deleted_at')->nullable();
        //     $table->string('file_path')->nullable();
        //     $table->text('body')->nullable();
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
