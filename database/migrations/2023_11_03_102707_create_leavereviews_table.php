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
            Schema::create('leavereviews', function (Blueprint $table) {
                $table->id();
     
                $table->string('applied_by');
     
     
     
                $table->date('from_date');
     
     
     
                $table->date('to_date');
     
     
     
                $table->integer('no_of_days');
     
     
     
                $table->string('applying_to');
     
                 $table->string('reason');
     
                $table->string('cc_to');
     
     
     
                $table->string('approved');
     
     
     
                $table->date('submitted');
     
                $table->timestamps();
            });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leavereviews');
    }
};
