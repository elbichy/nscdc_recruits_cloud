<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('name');
            $table->string('username')->index('users_username_index')->nullable();
            $table->string('email')->unique('users_email_unique');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->date('dob')->nullable();
            $table->string('sex')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('date_of_marriage')->nullable();
            $table->string('name_of_spouse')->nullable();
            $table->string('religion')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('genotype')->nullable();
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('soo')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('lgoo')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('residential_address')->nullable();
            $table->string('permanent_address')->nullable();
            $table->unsignedBigInteger('service_number')->index('users_service_number_index');
            $table->string('cadre')->nullable();
            $table->integer('gl')->nullable();
            $table->integer('step')->nullable();
            $table->string('rank_full')->nullable();
            $table->string('rank_short')->nullable();
            $table->date('dofa')->nullable();
            $table->date('doc')->nullable();
            $table->date('dopa')->nullable();
            $table->string('paypoint')->nullable();
            $table->string('salary_structure')->nullable();
            $table->string('bank')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bvn')->nullable();
            $table->string('ippis_number')->nullable();
            $table->string('nin_number')->nullable();
            $table->string('nhis_number')->nullable();
            $table->string('nhf')->nullable();
            $table->string('pfa')->nullable();
            $table->string('pen_number')->nullable();
            $table->string('current_formation')->nullable();
            $table->string('current_department')->nullable();
            $table->string('specialization')->nullable();
            $table->string('status')->default('active');
            $table->string('passport')->nullable();
            $table->integer('role')->default(0)->nullable();
            $table->boolean('synched')->default(0);
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
        Schema::dropIfExists('users');
    }
}
