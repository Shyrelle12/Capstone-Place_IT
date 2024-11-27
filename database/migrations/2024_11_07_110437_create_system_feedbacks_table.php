<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_feedback', function (Blueprint $table) {
            $table->id('feedbackID');
            $table->unsignedBigInteger('space_owner_id')->nullable(); 
            $table->foreign('space_owner_id')->references('userID')->on('users')->nullOnDelete();
            $table->text('feedback_content'); 
            $table->integer('rating')->nullable(); 
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_feedback');
    }
};
