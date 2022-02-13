<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConversionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversions', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->integer('serial_no')->nullable();
            $table->string('svc_no')->nullable();
            $table->string('name')->nullable();
            $table->string('dob')->nullable();
            $table->string('soo')->nullable();
            $table->string('command')->nullable();
            $table->string('command_type')->nullable();
            $table->string('additional_qual')->nullable();
            $table->year('qual_year')->nullable();
            $table->string('dofa')->nullable();
            $table->string('dopa')->nullable();
            $table->string('entry_rank')->nullable();
            $table->string('present_rank_full')->nullable();
            $table->string('present_rank_short')->nullable();
            $table->integer('old_gl')->nullable();
            $table->string('conversion_rank_full')->nullable();
            $table->string('conversion_rank_short')->nullable();
            $table->integer('new_gl')->nullable();
            $table->string('effective_date')->nullable();
            $table->string('type')->nullable();
            $table->string('ref_number')->nullable();
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
        Schema::dropIfExists('conversions');
    }
}
