<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EventChangesAndGaggles extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (Schema::hasTable('events')) {
			Schema::table('events', function (Blueprint $table) {
				$table->string('organiser_name')->nullable();
				$table->string('organiser_phone')->nullable();
			});
		}

		// gaggles are lists of aircraft e.g. what a club owns, or which aircraft are in a comp
		if (!Schema::hasTable('gaggles')) {
			Schema::create('gaggles', function (Blueprint $table) {
				$table->increments('id');
				$table->string('name')->nullable();
				$table->string('slug')->nullable();
				$table->integer('org_id')->nullable();
				$table->integer('user_id')->nullable();
				$table->timestamps();
				$table->softDeletes();
			});
		}

		// gaggles are lists of aircraft e.g. what a club owns, or which aircraft are in a comp
		if (!Schema::hasTable('aircraft_gaggle')) {
			Schema::create('aircraft_gaggle', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('aircraft_id');
				$table->integer('gaggle_id');
				$table->timestamps();
			});
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		if (Schema::hasTable('events')) {
			Schema::table('events', function (Blueprint $table) {
				$table->dropColumn('organiser_name');
				$table->dropColumn('organiser_phone');
			});
		}

		Schema::dropIfExists('gaggles');
		
		Schema::dropIfExists('aircraft_gaggle');
	}
}
