<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payorders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('quoteid');
            $table->string('invoicedate');
            $table->string('quotedate');
            $table->string('razorid');
            $table->string('city');
            $table->timestamps();

            $table->foreign('quoteid')->references('id')->on('quotations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payorders', function (Blueprint $table) {
            $table->dropForeign(['quoteid']);
        });
        Schema::dropIfExists('payorders');
    }
}
