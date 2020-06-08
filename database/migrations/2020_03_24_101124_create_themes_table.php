<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('theme_name');
            $table->string('theme_type');
            $table->integer('city')->default(1);
            $table->timestamps();

            $table->unique(['theme_name','theme_type', 'city']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('themes'))
        {
            Schema::table('themes', function(Blueprint $table)
            {
                //Put the index back when the migration is rolled back
                $table->dropUnique(['theme_name','theme_type','city']);
            });
        }

        Schema::dropIfExists('themes');
    }
}
