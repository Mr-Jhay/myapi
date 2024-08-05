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
        Schema::create('addchoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tblquestion_id');
            $table->string('choices');
            $table->timestamps();
            $table->foreign('tblquestion_id')->references('id')->on('tblquestion')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addchoices');
    }
};
