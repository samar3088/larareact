<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlineInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_inquiries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('created_users');
            $table->unsignedBigInteger('quotation');
            $table->integer('discount');
            $table->string('amount', 20);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('quotation')->references('id')->on('quotations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('online_inquiries', function (Blueprint $table) {
            $table->dropForeign(['quotation']);
        });

        Schema::dropIfExists('online_inquiries');
    }
}
