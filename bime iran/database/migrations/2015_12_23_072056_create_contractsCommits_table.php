<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractsCommitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contractsCommits', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('commit_id');
            $table->integer('contract_id');
            $table->string('farnshiz_control');
            $table->integer('insured_f');
            $table->integer('depend_f');
            $table->integer('non_depend_f');
            $table->string('unit');
            $table->integer('max_commit');
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
        Schema::drop('contractsCommits');
    }
}
