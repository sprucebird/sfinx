<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRFIDSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('r_f_i_d_s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('RFID')->unique();
            $table->unsignedBigInteger('Owner')->nullable();
            $table->string('Type')->default('card');
            $table->bigInteger('timesChecked')->nullable();
            $table->string('Status')->default('ACTIVE');
            $table->timestamps();
            $table->foreign("Owner")->references('id')->on("dancers")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('r_f_i_d_s');
    }
}
