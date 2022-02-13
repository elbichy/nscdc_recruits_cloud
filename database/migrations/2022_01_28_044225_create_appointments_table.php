<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('tsa');
            $table->string('num');
            $table->string('application_code');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('gender')->nullable();
            $table->string('position')->nullable();
            $table->string('state')->nullable();
            $table->string('lga')->nullable();
            $table->string('time')->nullable();
            $table->string('year')->nullable()->default(2019);
            $table->string('date')->nullable();
            $table->string('day')->nullable();
            $table->string('amount')->nullable();
            $table->string('id_number')->nullable();
            $table->longText('barcode')->nullable();
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
        Schema::dropIfExists('appointments');
    }
}
