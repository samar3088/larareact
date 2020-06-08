<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('package_name')->nullable();
            $table->string('package_image')->nullable();
            $table->integer('city');
            $table->string('description');

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->unique(['package_name', 'city']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('packages'))
        {
            Schema::table('packages', function(Blueprint $table)
            {
                //Put the index back when the migration is rolled back
                $table->dropUnique(['package_name','city']);
            });
        }

        Schema::dropIfExists('packages');
    }
}
