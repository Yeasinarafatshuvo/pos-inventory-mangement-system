<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCRMProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('c_r_m__proposals', function (Blueprint $table) {
            $table->id();
            $table->string("client_title");
            $table->int("client_id");
            $table->string("proposal_subject");
            $table->longText("proposed_product_name");
            $table->date("proposed_expired_date");
            $table->string("proposed_status");
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
        Schema::dropIfExists('c_r_m__proposals');
    }
}
