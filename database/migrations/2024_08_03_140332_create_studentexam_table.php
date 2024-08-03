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
        Schema::create('studentexam', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tblstudent_id');
            $table->unsignedBigInteger('tblschedule_id');
            $table->timestamps();
    
            $table->foreign('tblstudent_id')->references('id')->on('tblstudent')->onDelete('cascade');
            $table->foreign('tblschedule_id')->references('id')->on('tblschedule')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studentexam');
    }
};
