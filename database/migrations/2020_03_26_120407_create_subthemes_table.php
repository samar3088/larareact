<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubthemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subthemes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('theme_id')->nullable(false);
            $table->string('type', 20)->default('Q');
            $table->string('sub_theme_name')->nullable();
            $table->string('actual_price', 20)->nullable();
            $table->string('discounted_price', 20)->nullable();
            $table->string('label', 50)->nullable();
            $table->string('particular')->nullable();
            $table->string('rating', 20)->nullable();
            $table->string('views', 20)->nullable();
            $table->string('file')->nullable();
            $table->mediumText('description')->nullable();
            $table->mediumText('what_included')->nullable();
            $table->mediumText('need_to_know')->nullable();
            $table->integer('city')->default(1);
            $table->timestamps();

            //$table->unique(['sub_theme_name', 'city', 'actual_price']);
            $table->foreign('theme_id')->references('id')->on('themes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /* if (!Schema::hasTable('subthemes'))
        {
            Schema::table('subthemes', function(Blueprint $table)
            {
                //Put the index back when the migration is rolled back
                $table->dropUnique(['sub_theme_name','city','actual_price']);
            });
        } */
        Schema::table('subthemes', function (Blueprint $table) {
            $table->dropForeign(['theme_id']);
        });
        Schema::dropIfExists('subthemes');
    }
}
