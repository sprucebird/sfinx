<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DancerTraining extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('dancer_training', function (Blueprint $table) {
          $table->unsignedBigInteger('dancer_id');
          $table->unsignedBigInteger('trainings_id');
          $table->foreign('dancer_id')->references('id')->on('dancers')->onDelete('cascade');
          $table->foreign('trainings_id')->references('id')->on('trainings')->onDelete('cascade');
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
