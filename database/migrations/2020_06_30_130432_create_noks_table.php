<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * 
     * 
     * command designation	position_held	duration_from	duration_to
     * 
     * 		nok_phone

     * 
     */
    public function up()
    {
        Schema::create('noks', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('name')->nullable();
            $table->string('relationship')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });

        Schema::table('noks', function ($table) {
            $table->foreign('user_id')
            ->references('id')->on('users')
            ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('noks');
    }
}
