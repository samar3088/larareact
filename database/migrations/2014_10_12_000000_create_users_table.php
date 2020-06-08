<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('role_id')->default(2);
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('mobile')->nullable();
            $table->smallInteger('discount')->default('10');
            $table->string('thru_register')->default('no');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('toss_actual_pass')->nullable();
            $table->string('image')->default('default.png');
            $table->text('about')->nullable();
            $table->rememberToken();
            //$table->timestamps();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
