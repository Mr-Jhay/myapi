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
        Schema::create('tblsubject', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->string('subjectname');
            $table->string('yearlevel');
            $table->string('strand');
            $table->string('semester');
            $table->integer('gen_code'); // Changed from string to integer
            $table->string('up_img')->nullable(); // Added nullable for optional image field
            $table->timestamps();

            $table->foreign('teacher_id')->references('id')->on('tblteacher')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tblsubject');
    }
};
