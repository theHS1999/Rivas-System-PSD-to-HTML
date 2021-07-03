<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsuredsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insureds', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('insure_id');
            $table->integer('contract_id');
            $table->string('melli_code');
            $table->string('personal_code');
            $table->string('fname');
            $table->string('lname');
            $table->string('father_name');
            $table->string('birth_date');
            $table->string('birth_cert_num');
            $table->string('gender');
            $table->string('marrige_status');
            $table->string('employed_date');
            $table->string('janbaz_percent');
            $table->string('base_insure');
            $table->string('insure_num');
            $table->string('group');
            $table->string('bank');
            $table->string('account');
            $table->string('phone');
            $table->string('mobile');
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
        Schema::drop('insureds');
    }
}
