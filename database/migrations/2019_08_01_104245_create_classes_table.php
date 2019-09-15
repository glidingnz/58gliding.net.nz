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
            $table->text('description')->nullable();
            $table->json('attribute_1')->nullable();
            $table->json('attribute_2')->nullable();
            $table->json('attribute_3')->nullable();
            $table->json('attribute_4')->nullable();
            $table->json('attribute_5')->nullable();
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
