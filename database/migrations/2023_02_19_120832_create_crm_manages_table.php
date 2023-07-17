<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrmManagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_manages', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->integer('registered_by')->nullable();
            $table->string('contact_person_details')->nullable();
            $table->string('reference_by')->nullable();
            $table->string('bank_information')->nullable();
            $table->string('trade_licence')->nullable();
            $table->string('tin_number')->nullable();
            $table->string('bin_number')->nullable();
            $table->string('extra_field_details')->nullable();
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
        Schema::dropIfExists('crm_manages');
    }
}
