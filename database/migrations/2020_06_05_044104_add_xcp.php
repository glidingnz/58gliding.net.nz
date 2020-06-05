<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Rating;
use App\Models\RatingMember;

class AddXcp extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		// create the new ratings
		$rating = new Rating;
		$rating->name = "Cross Country Pilot";
		$rating->default_expires = null;
		$rating->save();

		$rating = new Rating;
		$rating->name = "Solo Pilot";
		$rating->default_expires = null;
		$rating->save();

		$rating = new Rating;
		$rating->name = "Soaring Pilot";
		$rating->default_expires = null;
		$rating->save();

		// upgrade the ratings table to include a number for QGPs and XCPs
		Schema::table('rating_member', function (Blueprint $table) {
			$table->integer('number')->nullable();
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}
}
