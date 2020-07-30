<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Entriesv2 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		// add event entry agreemenet field
		if (Schema::hasTable('events')) {
			Schema::table('events', function (Blueprint $table) {
				$table->text('entry_conditions')->nullable();

				// what kind of catering does the event have?
				$table->boolean('catering_lunches')->default(false);
				$table->boolean('catering_dinners')->default(false);
				$table->boolean('catering_breakfasts')->default(false);
				$table->boolean('catering_final_dinner')->default(false);
			});
		}

		Schema::create('entries2', function (Blueprint $table) {
			$table->increments('id');
			$table->string('editcode')->nullable();
			$table->integer('event_id')->unsigned()->nullable();
			$table->integer('classes_id')->unsigned()->nullable();
			$table->integer('member_id')->unsigned()->nullable();
			$table->integer('user_id')->unsigned()->nullable();
			$table->integer('aircraft_id')->unsigned()->nullable();

			// type of entry e.g. pilot, tow pilot, helper
			$table->string('entry_type')->nullable();

			// status of entry e.g. tenative, confirmed, cancelled
			$table->string('status')->nullable();

			// only used for visiting pilots/helpers who aren't in the GNZ database yet. Otherwise member ID is all they need.
			$table->boolean('gnz_member')->default(true); // if they are or are not a GNZ member
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->string('mobile')->nullable();
			$table->string('email')->nullable();

			$table->string('emergency_contact')->nullable();
			$table->string('emergency_mobile')->nullable();
			$table->string('emergency_phone')->nullable();
			$table->string('emergency_email')->nullable();
			$table->string('emergency_relationship')->nullable();

			$table->string('wingspan')->nullable();
			$table->string('handicap')->nullable();
			$table->boolean('winglets')->default(false);

			$table->string('crew_name')->nullable();
			$table->string('crew_mobile')->nullable();
			$table->string('car_plate')->nullable();
			$table->string('car_details')->nullable();

			// catering e.g. some, all, none
			$table->string('catering_lunches')->nullable();
			$table->string('catering_dinners')->nullable();
			$table->string('catering_breakfasts')->nullable();

			$table->integer('catering_final_dinner')->default(false);
			$table->string('catering_notes')->nullable();

			$table->boolean('signed')->default(false);
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
		Schema::dropIfExists('entries2');

		if (Schema::hasTable('events')) {
			Schema::table('events', function (Blueprint $table) {
				$table->dropColumn('entry_conditions');
				$table->dropColumn('catering_lunches');
				$table->dropColumn('catering_dinners');
				$table->dropColumn('catering_breakfasts');
				$table->dropColumn('catering_final_dinner');
			});
		}
	}
}
