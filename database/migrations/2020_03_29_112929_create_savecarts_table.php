<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavecartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('savecarts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ordertosave', 20);
            $table->string('event_date', 50)->nullable();
            $table->mediumText('item');
            $table->mediumText('added_item');
            $table->integer('manual_discount')->default(0);
            $table->string('discount_percent', 20)->nullable();
            $table->string('discount_amnt', 20)->nullable();
            $table->string('total_price', 50)->nullable();
            $table->string('crew_cost', 75)->nullable();
            $table->string('transport_cost', 75)->nullable();
            $table->tinyInteger('add_gst')->default(0);
            $table->string('cityname', 50)->nullable();
            $table->string('cityid', 10)->nullable();
            $table->integer('no_of_days')->nullable();
            $table->string('event_coordinate', 20)->default(0);
            $table->mediumText('remarks')->nullable();
            $table->integer('package_detail_id')->nullable();
            $table->string('package_price', 20)->default(0);
            $table->string('quote_type', 20)->default('qtsuccessownagent');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('savecarts');
    }
}
