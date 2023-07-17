<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayslipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->integer('automated_user_id');
            $table->string('attendance_year');
            $table->string('paymentMethod');
            $table->string('check_details')->nullable();
            $table->string('attendance_month');
            $table->double('mobile_allowance')->default(0);
            $table->double('lunch_allowance')->default(0);
            $table->double('festibal_allowance')->default(0);
            $table->double('other_allowance')->default(0);
            $table->double('provident_fund')->default(0);
            $table->double('delay')->default(0);
            $table->double('absence')->default(0);
            $table->double('loan_adjust')->default(0);
            $table->double('advance_adjust')->default(0);
            $table->double('total_earnings_value')->default(0);
            $table->double('total_deduction')->default(0);
            $table->double('total_net_pay')->default(0);
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
        Schema::dropIfExists('payslips');
    }
}
