<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookinglistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookinglists', function (Blueprint $table) {
            $table->id();
            $table->string('delivery_man')->nullable();
            $table->string('order_id')->nullable();
            $table->string('date')->nullable();
            $table->string('slot')->nullable();
            $table->string('slug')->nullable();;
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
        Schema::dropIfExists('bookinglists');
    }
}
