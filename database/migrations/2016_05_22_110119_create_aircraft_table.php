<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Classes\LoadAircraft;

class CreateAircraftTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('aircraft', function (Blueprint $table) {
			$table->increments('id');
			$table->string('rego')->unique();
			$table->string('contest_id')->nullable()->default('');
			$table->string('manufacturer')->nullable()->default('');
			$table->string('model')->nullable()->default('');
			$table->string('serial')->nullable()->default('');
			$table->string('mctow')->nullable()->default('');
			$table->string('class')->nullable()->default('');
			$table->string('transponder')->nullable()->default('');
			$table->string('trailer')->nullable()->default('');
			$table->string('owner')->nullable()->default('');
			$table->string('location')->nullable()->default('');
			$table->date('annual_inspection_due')->nullable()->default(NULL);
			$table->date('annual_airworthiness_due')->nullable()->default(NULL);
			$table->date('radio_due')->nullable()->default(NULL);
			$table->date('transponder_due')->nullable()->default(NULL);
			$table->date('altimeter_due')->nullable()->default(NULL);
			$table->integer('seats')->nullable()->default(1);
			$table->boolean('towplane')->nullable()->default(0);
			$table->boolean('self_launcher')->nullable()->default(0);
			$table->boolean('sustainer')->nullable()->default(0);
			$table->boolean('retractable')->nullable()->default(0);
			$table->boolean('vintage')->nullable()->default(0);
			$table->boolean('jet')->nullable()->default(0);
			$table->boolean('electric')->nullable()->default(0);
			$table->timestamps();
		});

		$aircraftLoader = new LoadAircraft();
		$aircraftLoader->load_db_from_caa();
		$aircraftLoader->import_caa_db();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('aircraft');
	}
}
