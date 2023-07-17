<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCRMRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_r_m__reminders', function (Blueprint $table) {
            $table->id();
            $table->string('note');
            $table->string('date');
            $table->string('time');
            $table->string('status');
            $table->integer('assign_to');
            $table->integer('assign_by');
            $table->integer('customer_id');
            $table->integer('interested_product');
            $table->integer('created_by');
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
        Schema::dropIfExists('c_r_m__reminders');
    }
}
