<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('package_detail_id')->nullable();
            $table->mediumText('item');
            $table->string('name')->nullable();
            $table->string('company')->nullable();
            $table->string('email', 190)->nullable();
            $table->string('mobile', 25)->nullable();
            $table->string('pdf')->nullable();
            $table->string('event_date', 50)->nullable();
            $table->integer('no_of_days')->nullable();
            $table->string('event_coordinate', 20)->nullable();
            $table->string('total_price', 50)->nullable();
            $table->string('new_file', 100)->nullable();
            $table->string('statussel', 50)->nullable();
            $table->mediumText('description');
            $table->timestamp('update_time')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->string('order_confirm', 50)->nullable();
            $table->string('completesel', 50)->nullable();
            $table->string('order_complete', 50)->nullable();
            $table->mediumText('added_item')->nullable();
            $table->string('crew_cost', 50)->nullable();
            $table->string('transport_cost', 50)->nullable();
            $table->tinyInteger('add_gst')->default(0);
            $table->integer('package_price')->nullable()->default(0);
            $table->integer('manual_discount')->default(0);
            $table->string('quote_type', 20)->nullable();
            $table->string('quote_by', 50)->nullable();
            $table->string('payment_received', 20)->nullable();
            $table->string('payment_balance', 50)->nullable();
            $table->string('quote_person')->nullable();
            $table->string('quote_contact', 10)->nullable();
            $table->string('payment_collected', 20)->nullable();
            $table->mediumText('cc_mails')->nullable();
            $table->mediumText('bcc_mails')->nullable();
            $table->date('created_at')->nullable();
            $table->integer('event_expenses')->default(0);
            $table->integer('event_gst')->default(0);
            $table->mediumText('event_remarks')->nullable();
            $table->string('booking_type')->nullable()->default('general');
            $table->string('show_amount')->default('yes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotations');
    }
}
