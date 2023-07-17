<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoneyPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('money_payments', function (Blueprint $table) {
            $table->id();
            $table->string('order_invoice');
            $table->string('total_payment_in_number');
            $table->string('maakview_account_name');
            $table->string('total_payment_in_word');
            $table->string('maakview_checque_number');
            $table->string('maakview_cheque_date');
            $table->string('bill_info');
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
        Schema::dropIfExists('money_payments');
    }
}
