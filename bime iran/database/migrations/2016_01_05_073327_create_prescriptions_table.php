<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescriptions', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('serviceCenter_id');
            $table->integer('insured_id');
            $table->string('status');
            $table->string('city');
            $table->string('doctor_type');
            $table->string('doctor_expertise');
            $table->integer('total');
            $table->integer('total_others_difference');
            $table->integer('total_franshiz');
            $table->integer('insured_pay');
            $table->integer('total_base_insure');
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
        Schema::drop('prescriptions');
    }
}
