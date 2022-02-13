<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_ranks', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('cadre');
            $table->integer('gl');
            $table->string('full_title');
            $table->string('short_title');
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
        Schema::dropIfExists('new_ranks');
    }
}
