<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('package_name')->nullable();
            $table->string('no_of_pax', 100)->nullable();
            $table->string('indoor_outdoor')->nullable();
            $table->string('price')->nullable();
            $table->mediumText('package_include')->nullable();
            $table->integer('city');
            $table->timestamps();

            $table->unique(['package_name', 'city','no_of_pax']);
            $table->foreign('package_name')->references('id')->on('packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('package_details'))
        {
            Schema::table('package_details', function(Blueprint $table)
            {
                //Put the index back when the migration is rolled back
                $table->dropUnique(['package_name','city','no_of_pax']);
            });

            Schema::table('package_details', function (Blueprint $table) {
                $table->dropForeign(['package_name']);
            });
        }

        Schema::dropIfExists('package_details');
    }
}
