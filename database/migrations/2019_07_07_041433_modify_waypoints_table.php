<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyWaypointsTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {

        Schema::disableForeignKeyConstraints();

        // Faster to drop the table and recreate. Just Import the waypoints again afterwards

        Schema::drop('waypoints');

        Schema::create('waypoints', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',80);
            $table->string('code',6);
            $table->string('country',2)->nullable()->default('NZ');
            $table->decimal('lat',9,2);
            $table->decimal('long',9,2);
            $table->integer('elevation')->nullable();
            $table->integer('style')->nullable();
            $table->integer('direction')->nullable();
            $table->integer('length')->nullable();
            $table->decimal('frequency',5,2)->nullable();
            $table->string('description',140)->nullable();
            $table->timestamps();
        });

        Schema::drop('cups');
        // a group of waypoints shall be called a 'cup' after the famous cup file
        Schema::create('cups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',80);
            $table->string('description',140);
            $table->timestamps();
        });

        Schema::drop('cup_waypoint');
        // identify which waypoint is in which cup
        Schema::create('cup_waypoint', function (Blueprint $table) {
            $table->integer('cup_id')->unsigned();
            $table->integer('waypoint_id')->unsigned();
            $table->boolean('owner')->default(false);
            $table->timestamps();
            $table->unique(['cup_id','waypoint_id']);
            $table->foreign('cup_id')->references('id')->on('cups')->onDelete('cascade');
            $table->foreign('waypoint_id')->references('id')->on('waypoints')->onDelete('cascade');
        });
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::drop('waypoints');
        Schema::drop('cups');
        Schema::drop('cup_waypoint');
    }
}
