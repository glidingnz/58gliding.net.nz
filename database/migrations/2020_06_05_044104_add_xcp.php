<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Member;
use App\Models\Rating;
use App\Models\Badge;
use App\Models\BadgeMember;
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
		if (!Rating::where('name', '=', 'Cross Country Pilot')->exists()) {
			$rating = new Rating;
			$rating->name = "Cross Country Pilot";
			$rating->default_expires = null;
			$rating->save();
		}

		if (!Rating::where('name', '=', 'Solo Pilot')->exists()) {
			$rating = new Rating;
			$rating->name = "Solo Pilot";
			$rating->default_expires = null;
			$rating->save();
		}

		if (!Rating::where('name', '=', 'Soaring Pilot')->exists()) {
			$rating = new Rating;
			$rating->name = "Soaring Pilot";
			$rating->default_expires = null;
			$rating->save();
		}

		// upgrade the ratings table to include a number for QGPs and XCPs
		Schema::table('rating_member', function (Blueprint $table) {
			$table->integer('number')->nullable();
		});

		// import all QGPs from old database
		if ($members = Member::whereNotNull('qgp_number')->get())
		{
			foreach ($members AS $member)
			{

				// check if we have a QGP entry for this member
				if (!$rating = RatingMember::where('member_id', $member->id)->where('rating_id', 1)->first())
				{
					$rating = new RatingMember;
				}

				// update the current or new rating details
				$rating->member_id =  $member->id;
				$rating->rating_id =  1; //1=QGP
				$rating->number = $member->qgp_number; //1=QGP
				$rating->awarded = $member->date_of_qgp;
				$rating->authorising_member_id = 0; // no member
				$rating->granted_by_user_id = 0; // root
				$rating->save();
			}
		}

		// remove QGP from badges system
		// first get the QGP badge number
		$qgp_badge = Badge::where('name', 'QGP')->first();
		// then delete all badges
		BadgeMember::where('badge_id', $qgp_badge->id)->delete();
		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// remove number
		Schema::table('rating_member', function ($table) {
			$table->dropColumn('number')->nullable();
		});

	}
}
