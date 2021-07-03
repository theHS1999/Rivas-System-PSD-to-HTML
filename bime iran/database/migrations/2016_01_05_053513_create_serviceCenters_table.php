<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serviceCenters', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name');
            $table->string('technical_user');
            $table->string('sahebe_emtiaz');
            $table->string('medical_code');
            $table->text('address');
            $table->string('phone');
            $table->string('fax');
            $table->string('mobile');
            $table->string('website');
            $table->text('insures_under_contract');
            $table->string('shift');
            $table->string('bank');
            $table->string('account_num');
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
        Schema::drop('serviceCenters');
    }
}
