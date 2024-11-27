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
        Schema::create('billing_details', function (Blueprint $table) {
            $table->id('billingDetailID'); 
            $table->unsignedBigInteger('user_id'); // Foreign key for users
            $table->unsignedBigInteger('rental_agreement_id'); // Foreign key for rental agreements
            $table->string('gcash_number'); // GCash number field
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('userID')->on('users')->onDelete('cascade');
            $table->foreign('rental_agreement_id')->references('rentalAgreementID')->on('rental_agreements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billing_details');
    }
};
