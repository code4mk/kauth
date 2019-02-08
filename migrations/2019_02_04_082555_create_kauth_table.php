<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKauthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kauth', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tokon',255)->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('iat')->nullable();
            $table->integer('exp')->nullable();
            $table->string('ip')->nullable();
            $table->string('browser')->nullable();
            $table->string('device')->nullable();
            $table->string('os')->nullable();
            $table->string('login')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kauth');
    }
}
