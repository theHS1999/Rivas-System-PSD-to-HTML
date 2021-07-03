<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePresMedicinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presMedicines', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('pres_id');
            $table->integer('medicine_id');
            $table->string('medicine_name');
            $table->integer('medicine_price');
            $table->integer('count');
            $table->integer('open_market_price');
            $table->integer('total');
            $table->integer('others_difference');
            $table->integer('base_insure');
            $table->integer('franshiz');
            $table->integer('insured_pay');
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
        Schema::drop('presMedicines');
    }
}
