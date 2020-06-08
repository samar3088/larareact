<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorDetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_det', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('showtime');
            $table->string('selvalue');
            $table->string('selects');
            $table->string('name');
            $table->string('email');
            $table->string('mobile');
            $table->string('addressproof');
            $table->string('chequeproof');
            $table->string('panproof');
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
        Schema::dropIfExists('vendor_dets');
    }
}
