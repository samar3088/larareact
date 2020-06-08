<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubthemeimagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subthemeimages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sub_theme_id')->nullable(false);
            $table->string('path');
            $table->timestamps();

            $table->foreign('sub_theme_id')->references('id')->on('subthemes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subthemeimages', function (Blueprint $table) {
            $table->dropForeign(['sub_theme_id']);
        });
        Schema::dropIfExists('subthemeimages');
    }
}
