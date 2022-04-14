<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedeploymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('redeployments', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('type');
            $table->string('fullname');
            $table->unsignedBigInteger('service_number');
            $table->string('ref_number');
            $table->string('rank');
            $table->string('rank_acronym');
            $table->string('from');
            $table->string('to');
            $table->string('designation')->nullable();
            $table->string('reason')->nullable();
            $table->string('incharge')->nullable()->default('Commandant General');
            $table->string('signatory')->default('dcg');
            $table->boolean('financial_implication')->default(false);
            $table->longText('barcode');
            $table->tinyInteger('synched')->default(0);
            $table->softDeletes();
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
        Schema::connection('mysql2')->dropIfExists('redeployments');
    }
}
