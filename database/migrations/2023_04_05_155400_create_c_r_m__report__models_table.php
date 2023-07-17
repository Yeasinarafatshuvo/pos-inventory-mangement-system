<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCRMReportModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_r_m__report__models', function (Blueprint $table) {
            $table->id();            
            $table->string('user_id');
            $table->integer('number_of_whatsapp_sent')->nullable();
            $table->integer('number_of_whatsapp_response')->nullable();
            $table->string('whatsapp_comment')->nullable();
            $table->integer('number_of_email_sent')->nullable();
            $table->integer('number_of_email_response')->nullable();
            $table->string('email_comment')->nullable();
            $table->integer('number_of_phone_call')->nullable();
            $table->integer('number_of_phone_call_response')->nullable();
            $table->string('phone_call_comment')->nullable();
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
        Schema::dropIfExists('c_r_m__report__models');
    }
}
