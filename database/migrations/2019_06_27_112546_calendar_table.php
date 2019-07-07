<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Org;
use App\Models\Duty;

class CalendarTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('days')) {
			Schema::create('days', function(Blueprint $table)
			{
				$table->integer('id', true);
				$table->integer('org_id')->nullable();
				$table->integer('event_id')->nullable();
				$table->date('day_date');
				$table->boolean('active')->default(1);
				$table->text('description')->nullable();
				$table->boolean('trialflights')->default(1);
				$table->boolean('competition')->default(0);
				$table->boolean('training')->default(0);
				$table->boolean('winching')->default(0);
				$table->boolean('towing')->default(1);
				$table->string('status')->nullable()->default('flying');
				$table->boolean('cancelled')->default(0);
				$table->string('cancelled_reason')->nullable()->default('');
				$table->softDeletes();
				$table->timestamps();
			});
		}

		if (!Schema::hasTable('duties')) {
			Schema::create('duties', function(Blueprint $table)
			{
				$table->integer('id', true);
				$table->integer('org_id')->nullable();
				$table->text('name')->nullable();
				$table->softDeletes();
				$table->timestamps();
			});
		}

		if (!Schema::hasTable('rosters')) {
			Schema::create('rosters', function(Blueprint $table)
			{
				$table->integer('id', true);
				$table->integer('org_id')->nullable();
				$table->integer('day_id')->nullable();
				$table->date('day_date')->nullable();
				$table->text('duty_name')->nullable();
				$table->integer('member_id')->nullable();
				$table->text('helper_name')->nullable();
				$table->text('helper_mobile')->nullable();
				$table->softDeletes();
				$table->timestamps();
			});
		}


		$duty_names = Array('Instructor 1', 'Instructor 2', 'Tow Pilot','Winch Driver', 'Duty Pilot');

		// insert defalut duties for each of the orgs
		if ($orgs = Org::all())
		{
			foreach ($orgs AS $org)
			{
				foreach ($duty_names AS $name)
				{
					$duty = new Duty();
					$duty->org_id=$org->id;
					$duty->name = $name;
					$duty->save();
				}
			}
		}


	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::dropIfExists('days');
		Schema::dropIfExists('duties');
		Schema::dropIfExists('rosters');
	}
}
