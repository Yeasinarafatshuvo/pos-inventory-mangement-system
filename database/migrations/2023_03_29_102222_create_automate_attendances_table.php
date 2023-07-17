<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutomateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('automate_attendances', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->date('date_attendance')->nullable();
            $table->string('attendance_in_time')->nullable();
            $table->string('attendance_out_time')->nullable();
            $table->integer('approve_dellay')->nullable();
            $table->string('un_approve_dellay')->nullable();
            $table->integer('approve_leave')->nullable();
            $table->string('un_approve_leave')->nullable();
            $table->string('comments_prepared')->nullable();
            $table->string('comments_admin')->nullable();
            $table->string('comments_ceo')->nullable();
            $table->integer('total_approve_dellay')->nullable();
            $table->integer('total_un_approve_dellay')->nullable();
            $table->integer('total_approve_leave')->nullable();
            $table->integer('total_un_approve_leave')->nullable();
            $table->integer('total_absence')->nullable();
            $table->double('salary')->nullable();
            $table->double('deduction_amount')->nullable();
            $table->double('final_payamount')->nullable();
            $table->integer('in_out_edit_value')->default(0)->nullable();
            $table->integer('salary_generate_value')->default(0)->nullable();
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
        Schema::dropIfExists('automate_attendances');
    }
}
