<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassesTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',80)->nullable(false);
            $table->text('description');
            $table->bigInteger('contest_id')->unsigned()->nullable(false);
            $table->foreign('contest_id')->references('id')->on('contests');
            $table->json('attribute_1');
            $table->json('attribute_2');
            $table->json('attribute_3');
            $table->json('attribute_4');
            $table->json('attribute_5');
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
        Schema::dropIfExists('classes');
    }
}
