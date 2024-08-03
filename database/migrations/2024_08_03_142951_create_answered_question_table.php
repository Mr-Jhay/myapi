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
        Schema::create('answered_question', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tblquestion_id');
            $table->unsignedBigInteger('correctanswer_id');
            $table->unsignedBigInteger('tblstudent_id');
            $table->timestamps();

            $table->foreign('tblquestion_id')->references('id')->on('tblquestion')->onDelete('cascade');
            $table->foreign('correctanswer_id')->references('id')->on('correctanswer')->onDelete('cascade');
            $table->foreign('tblstudent_id')->references('id')->on('tblstudent')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answered_question');
    }
};
