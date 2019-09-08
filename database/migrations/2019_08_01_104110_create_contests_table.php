<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',80)->nullable(false);
            $table->text('description');
            $table->date('practice')->nullable();
            $table->date('start')->nullable(false);
            $table->date('end')->nullable(false);
            $table->string('website',140)->nullable();
            $table->string('contact',140)->nullable();
            $table->string('email',140)->nullable();
            $table->string('location',140)->nullable();
            $table->text('terms')->nullable();
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
        Schema::dropIfExists('contests');
    }
}
