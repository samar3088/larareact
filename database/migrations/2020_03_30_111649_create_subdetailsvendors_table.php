<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubdetailsvendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subdetailsvendors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vendortitle');
            $table->mediumText('include');
            $table->unsignedBigInteger('maincategory')->nullable();
            $table->string('price',100);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->foreign('vendortitle')->references('id')->on('mainvendors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('subdetailsvendors'))
        {
            Schema::table('subdetailsvendors', function (Blueprint $table) {
                $table->dropForeign(['vendortitle']);
            });
        }

        Schema::dropIfExists('subdetailsvendors');
    }
}
