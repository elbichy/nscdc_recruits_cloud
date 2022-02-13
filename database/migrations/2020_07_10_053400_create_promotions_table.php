<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->integer('serial_no')->nullable();
            $table->string('svc_no')->nullable();
            $table->string('name')->nullable();
            $table->string('dob')->nullable();
            $table->string('soo')->nullable();
            $table->string('command')->nullable();
            $table->string('command_type')->nullable();
            $table->string('dofa')->nullable();
            $table->string('dopa')->nullable();
            $table->string('present_rank_full')->nullable();
            $table->string('present_rank_short')->nullable();
            $table->integer('present_gl')->nullable();
            $table->string('promotion_rank_full')->nullable();
            $table->string('promotion_rank_short')->nullable();
            $table->integer('promotion_gl')->nullable();
            $table->string('effective_date')->nullable();
            $table->string('type')->nullable()->default('normal');
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
        Schema::dropIfExists('promotions');
    }
}
