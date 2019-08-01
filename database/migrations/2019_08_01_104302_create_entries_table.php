<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('contest_id')->unsigned()->nullable(false);
            $table->bigInteger('classes_id')->unsigned()->nullable(false);
            $table->foreign('contest_id')->references('id')->on('contests');
            $table->foreign('classes_id')->references('id')->on('classes');

            $table->string('pilot',80)->nullable(false);
            $table->string('pilot2',80)->nullable(true)->default('');

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
        Schema::dropIfExists('entries');
    }
}
