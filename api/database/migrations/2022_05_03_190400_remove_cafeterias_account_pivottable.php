<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCafeteriasAccountPivottable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('account_cafeteria');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('account_cafeteria', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('cafeteria_id');
            $table->foreign('cafeteria_id')->references('id')->on('cafeterias');
            $table->unsignedBigInteger('account_id');
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->float('annual_value');
        });
    }
}
