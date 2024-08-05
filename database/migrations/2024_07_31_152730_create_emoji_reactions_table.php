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
        Schema::create('emoji_reactions', function (Blueprint $table) {
            $table->id();
            $table->string('emp_id'); // Adjust the data type as needed
            $table->string('first_name');
            $table->string('last_name');
            $table->string('emoji_reaction');
            $table->foreign('emp_id')
            ->references('emp_id')
            ->on('employee_details')
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
        Schema::dropIfExists('emoji_reactions');
    }
};
