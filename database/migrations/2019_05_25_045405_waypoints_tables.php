<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WaypointsTables extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('waypoints', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name')->nullable();
			$table->string('code')->nullable();
			$table->string('country')->nullable();
			$table->string('lat')->nullable();
			$table->string('long')->nullable();
			$table->integer('elevation')->nullable();
			$table->integer('style')->nullable();
			$table->string('direction')->nullable();
			$table->integer('length')->nullable();
			$table->string('frequency')->nullable();
			$table->text('description')->nullable();
			$table->integer('number')->nullable();
			$table->integer('batch')->nullable();
			$table->timestamps();
		});

		DB::statement("ALTER TABLE waypoints ADD COLUMN location POINT");

		// a group of waypoints shall be called a 'cup' after the famous cup file
		Schema::create('cups', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name')->nullable();
			$table->integer('org_id')->nullable(); // a cup can be linked to a specific org
			$table->integer('event_id')->nullable(); // or event e.g. competition
		});

		// identify which waypoint is in which cup
		Schema::create('cup_waypoint', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('cup_id');
			$table->integer('waypoint_id');
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
