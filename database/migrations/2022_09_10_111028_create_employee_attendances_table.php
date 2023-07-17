<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('date_attendance')->nullable();
            $table->string('attendance_id')->nullable();
            $table->string('attendance_in_time')->nullable();
            $table->string('attendance_out_time')->nullable();
            $table->integer('approve_dellay')->nullable();
            $table->string('un_approve_dellay')->nullable();
            $table->string('un_approve_leave')->nullable();
            $table->integer('approve_leave')->nullable();
            $table->string('comments_prepared')->nullable();
            $table->string('comments_admin')->nullable();
            $table->string('comments_ceo')->nullable();
            $table->string('name')->nullable();
            $table->integer('total_approve_dellay')->nullable();
            $table->integer('total_un_approve_dellay')->nullable();
            $table->integer('total_approve_leave')->nullable();
            $table->integer('total_un_approve_leave')->nullable();
            $table->integer('total_absence')->nullable();
            $table->double('salary')->nullable();
            $table->double('deduction_amount')->nullable();
            $table->double('final_payamount')->nullable();
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
        Schema::dropIfExists('employee_attendances');
    }
}
