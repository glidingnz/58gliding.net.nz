<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEventsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('events')) {
			Schema::create('events', function (Blueprint $table) {
				$table->increments('id');
				$table->boolean('live')->default(false)->nullable();
				$table->boolean('share_gnz')->default(false)->nullable();
				$table->string('type')->nullable();
				$table->integer('org_id')->nullable();
				$table->string('name')->nullable();
				$table->string('slug')->nullable();
				$table->string('location')->nullable();
				$table->integer('waypoint_id')->nullable();
				$table->integer('organiser_user_id')->nullable();
				$table->integer('creator_user_id')->nullable();
				$table->string('website')->nullable();
				$table->string('email')->nullable();
				$table->string('instagram')->nullable();
				$table->string('facebook')->nullable();
				$table->date('start_date')->nullable();
				$table->date('end_date')->nullable();
				$table->time('start_time')->nullable();
				$table->time('end_time')->nullable();
				$table->date('earlybird')->nullable();
				$table->integer('practice_days')->nullable();
				$table->text('terms')->nullable();
				$table->text('details')->nullable();
				$table->decimal('cost')->nullable();
				$table->decimal('cost_earlybird')->nullable();
				$table->timestamps();
				$table->softDeletes();
			});
		}

		if (Schema::hasTable('contests')) {
			Schema::table('contests', function (Blueprint $table) {
				$table->integer('event_id')->nullable();
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

		Schema::dropIfExists('events');

		if (Schema::hasTable('contests')) {
			Schema::table('contests', function (Blueprint $table) {
				$table->dropColumn('event_id');
			});
		}
	}
}
