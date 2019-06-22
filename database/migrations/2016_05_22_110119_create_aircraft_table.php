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
			$table->string('contest_id')->default('');
			$table->string('manufacturer')->default('');
			$table->string('model')->default('');
			$table->string('serial')->default('');
			$table->string('mctow')->default('');
			$table->string('class')->default('');
			$table->string('transponder')->default('');
			$table->string('trailer')->default('');
			$table->string('owner')->default('');
			$table->string('location')->default('');
			$table->date('annual_inspection_due')->nullable()->default(NULL);
			$table->date('annual_airworthiness_due')->nullable()->default(NULL);
			$table->date('radio_due')->nullable()->default(NULL);
			$table->date('transponder_due')->nullable()->default(NULL);
			$table->date('altimeter_due')->nullable()->default(NULL);
			$table->integer('seats')->default(1);
			$table->boolean('towplane')->default(0);
			$table->boolean('self_launcher')->default(0);
			$table->boolean('sustainer')->default(0);
			$table->boolean('retractable')->default(0);
			$table->boolean('vintage')->default(0);
			$table->boolean('jet')->default(0);
			$table->boolean('electric')->default(0);
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
