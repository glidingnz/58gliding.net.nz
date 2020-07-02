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

		// keep track if a rating is able to be added or not. QGP is now archived.
		Schema::table('ratings', function (Blueprint $table) {
			$table->boolean('archived')->default(false);
			$table->boolean('numbered')->default(false);
		});

		// archive QGP
		$qgp = Rating::where('name', '=', 'QGP')->first();
		$qgp->archived = true;
		$qgp->numbered = true;
		$qgp->save();

		// create the new ratings
		if (!Rating::where('name', '=', 'Tow Pilot Instructor')->exists()) {
			$rating = new Rating;
			$rating->name = "Tow Pilot Instructor";
			$rating->default_expires = null;
			$rating->numbered = false;
			$rating->save();
		}

		if (!Rating::where('name', '=', 'X-Country Pilot')->exists()) {
			$rating = new Rating;
			$rating->name = "X-Country Pilot";
			$rating->default_expires = null;
			$rating->numbered = true;
			$rating->save();
		}

		if (!Rating::where('name', '=', 'Solo Pilot')->exists()) {
			$rating = new Rating;
			$rating->name = "Solo Pilot";
			$rating->default_expires = null;
			$rating->numbered = true;
			$rating->save();
		}

		if (!Rating::where('name', '=', 'Soaring Pilot')->exists()) {
			$rating = new Rating;
			$rating->name = "Soaring Pilot";
			$rating->default_expires = null;
			$rating->numbered = true;
			$rating->save();
		}

		if (!Rating::where('name', '=', 'Task Pilot')->exists()) {
			$rating = new Rating;
			$rating->name = "Task Pilot";
			$rating->default_expires = null;
			$rating->numbered = true;
			$rating->save();
		}

		if (!Rating::where('name', '=', 'Alpine Pilot')->exists()) {
			$rating = new Rating;
			$rating->name = "Alpine Pilot";
			$rating->default_expires = null;
			$rating->numbered = true;
			$rating->save();
		}

		// get the ratings for later
		$xcp = Rating::where('name', "X-Country Pilot")->first();
		$sop = Rating::where('name', "Solo Pilot")->first();
		$srp = Rating::where('name', "Soaring Pilot")->first();
		$tp = Rating::where('name', "Task Pilot")->first();
		$ap = Rating::where('name', "Alpine Pilot")->first();

		// upgrade the ratings table to include a number for QGPs and XCPs
		Schema::table('rating_member', function (Blueprint $table) {
			$table->integer('number')->nullable();
			$table->text('notes')->change()->nullable();
		});

		$xcp_number = 3500;

		// import all members from the old database. Chunk it so we don't run out of memory
		DB::table('gnz_member')->orderBy('id')->chunk(100, function ($members) use ($xcp, $sop, $srp, $tp, $ap, &$xcp_number) {
			foreach ($members AS $member)
			{
				// if the memebr has a QGP, then insert a new QGP
				if ($member->qgp_number!=null && $member->qgp_number>0)
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
					$rating->expires = '2020-06-15';
					$rating->authorising_member_id = 0; // no member
					$rating->granted_by_user_id = 0; // root
					$rating->save();
				}

				// if the pilot has a silver distance or greater, automatically grant them a XCP
				if ($member->silver_distance>0 || $member->gold_distance>0 || $member->diamond_distance_number>0 || $member->diamond_goal_number>0) {
					
					$rating = new RatingMember;
					$rating->member_id =  $member->id;
					$rating->rating_id =  $xcp->id; // the cross country pilot rating ID

					// if the member has a QGP, use that number, otherwise issue a new XCP number
					if ($member->qgp_number!=null && $member->qgp_number>0)
					{
						$rating->number = $member->qgp_number; // use the QGP number
					}
					else
					{
						$rating->number = $xcp_number;
						$xcp_number++;
					}
					
					$rating->awarded = '2020-06-15';
					$rating->authorising_member_id = 0; // no member
					$rating->granted_by_user_id = 0; // root
					$rating->notes = 'Converted from QGP';
					$rating->save();

					// add the solo and soaring pilot ratings too if they have XCP
					if (!RatingMember::where('member_id', $member->id)->where('rating_id', $sop->id)->exists())
					{
						$rating = new RatingMember;
						$rating->member_id =  $member->id;
						$rating->rating_id =  $sop->id; // the solo pilot rating ID
						$rating->awarded = '2020-06-15';
						$rating->authorising_member_id = 0; // no member
						$rating->granted_by_user_id = 0; // root
						$rating->notes = 'Converted from QGP';
						$rating->save();
					}

					// add the solo and soaring pilot ratings too if they have XCP
					if (!RatingMember::where('member_id', $member->id)->where('rating_id', $srp->id)->exists())
					{
						$rating = new RatingMember;
						$rating->member_id =  $member->id;
						$rating->rating_id =  $srp->id; // the solo pilot rating ID
						$rating->awarded = '2020-06-15';
						$rating->authorising_member_id = 0; // no member
						$rating->granted_by_user_id = 0; // root
						$rating->notes = 'Converted from QGP';
						$rating->save();
					}
				}

				// TODO: Check if they exist already or not
				// insert the other ratings into the new structure
				if ($member->instructor_trainer) {
					$rating = Rating::where('name', "Instructor Trainer")->first();

					// create only if it doesn't already exist
					if (!RatingMember::where('member_id', $member->id)->where('rating_id', $rating->id)->exists())
					{
						$rm = new RatingMember;
						$rm->member_id =  $member->id;
						$rm->rating_id =  $rating->id; // the solo pilot rating ID
						$rm->awarded = '2020-06-15';
						$rm->authorising_member_id = 0; // no member
						$rm->granted_by_user_id = 0; // root
						$rm->notes = 'Imported';
						$rm->save();
					}

					
				}

				if ($member->tow_pilot_instructor) {
					$rating = Rating::where('name', "Tow Pilot Instructor")->first();
					if (!RatingMember::where('member_id', $member->id)->where('rating_id', $rating->id)->exists())
					{
						$rm = new RatingMember;
						$rm->member_id =  $member->id;
						$rm->rating_id =  $rating->id; // the solo pilot rating ID
						$rm->awarded = '2020-06-15';
						$rm->authorising_member_id = 0; // no member
						$rm->granted_by_user_id = 0; // root
						$rm->notes = 'Imported';
						$rm->save();
					}
				}
				
				if ($member->aero_instructor) {
					$rating = Rating::where('name', "Aerobatic Instructor (Aero)")->first();
					if (!RatingMember::where('member_id', $member->id)->where('rating_id', $rating->id)->exists())
					{
						$rm = new RatingMember;
						$rm->member_id =  $member->id;
						$rm->rating_id =  $rating->id; // the solo pilot rating ID
						$rm->awarded = '2020-06-15';
						$rm->authorising_member_id = 0; // no member
						$rm->granted_by_user_id = 0; // root
						$rm->notes = 'Imported';
						$rm->save();
					}
				}
				
				if ($member->advanced_aero_instructor) {
					$rating = Rating::where('name', "Aerobatic Instructor (Advanced Aero)")->first();

					if (!RatingMember::where('member_id', $member->id)->where('rating_id', $rating->id)->exists())
					{
						$rm = new RatingMember;
						$rm->member_id =  $member->id;
						$rm->rating_id =  $rating->id; // the solo pilot rating ID
						$rm->awarded = '2020-06-15';
						$rm->authorising_member_id = 0; // no member
						$rm->granted_by_user_id = 0; // root
						$rm->notes = 'Imported';
						$rm->save();
					}
				}
				
				if ($member->auto_tow) {
					$rating = Rating::where('name', "Auto Launch")->first();
					if (!RatingMember::where('member_id', $member->id)->where('rating_id', $rating->id)->exists())
					{
						$rm = new RatingMember;
						$rm->member_id =  $member->id;
						$rm->rating_id =  $rating->id; // the solo pilot rating ID
						$rm->awarded = '2020-06-15';
						$rm->authorising_member_id = 0; // no member
						$rm->granted_by_user_id = 0; // root
						$rm->notes = 'Imported';
						$rm->save();
					}
				}
				
				if ($member->tow_pilot) {
					$rating = Rating::where('name', "Tow Pilot")->first();
					if (!RatingMember::where('member_id', $member->id)->where('rating_id', $rating->id)->exists())
					{
						$rm = new RatingMember;
						$rm->member_id =  $member->id;
						$rm->rating_id =  $rating->id; // the solo pilot rating ID
						$rm->awarded = '2020-06-15';
						$rm->authorising_member_id = 0; // no member
						$rm->granted_by_user_id = 0; // root
						$rm->notes = 'Imported';
						$rm->save();
					}
				}
				
				if ($member->insttrain) {
					$rating = Rating::where('name', "Instructor Trainer")->first();
					if (!RatingMember::where('member_id', $member->id)->where('rating_id', $rating->id)->exists())
					{
						$rm = new RatingMember;
						$rm->member_id =  $member->id;
						$rm->rating_id =  $rating->id; // the solo pilot rating ID
						$rm->awarded = '2020-06-15';
						$rm->authorising_member_id = 0; // no member
						$rm->granted_by_user_id = 0; // root
						$rm->notes = 'Imported';
						$rm->save();
					}
				}
				
				if ($member->self_launch) {
					$rating = Rating::where('name', "Powered Glider")->first();
					if (!RatingMember::where('member_id', $member->id)->where('rating_id', $rating->id)->exists())
					{
						$rm = new RatingMember;
						$rm->member_id =  $member->id;
						$rm->rating_id =  $rating->id; // the solo pilot rating ID
						$rm->awarded = '2020-06-15';
						$rm->authorising_member_id = 0; // no member
						$rm->granted_by_user_id = 0; // root
						$rm->notes = 'Imported';
						$rm->save();
					}
				}
				
				if ($member->winch_rating) {
					$rating = Rating::where('name', "Winch Launch")->first();
					if (!RatingMember::where('member_id', $member->id)->where('rating_id', $rating->id)->exists())
					{
						$rm = new RatingMember;
						$rm->member_id =  $member->id;
						$rm->rating_id =  $rating->id; // the solo pilot rating ID
						$rm->awarded = '2020-06-15';
						$rm->authorising_member_id = 0; // no member
						$rm->granted_by_user_id = 0; // root
						$rm->notes = 'Imported';
						$rm->save();
					}
				}
				
				if ($member->instructor) {
					switch ($member->instructor_rating) {
						case 'A': $rating = Rating::where('name', "Instructor A Cat")->first(); break;
						case 'B': $rating = Rating::where('name', "Instructor B Cat")->first(); break;
						case 'C': $rating = Rating::where('name', "Instructor C Cat")->first(); break;
						case 'D': $rating = Rating::where('name', "Instructor D Cat")->first(); break;
					}
					
					if (!RatingMember::where('member_id', $member->id)->where('rating_id', $rating->id)->exists())
					{
						$rm = new RatingMember;
						$rm->member_id =  $member->id;
						$rm->rating_id =  $rating->id; // the solo pilot rating ID
						$rm->awarded = '2020-06-15';
						$rm->authorising_member_id = 0; // no member
						$rm->granted_by_user_id = 0; // root
						$rm->notes = 'Imported';
						$rm->save();
					}
				}


			}
		});

		// remove QGP from badges system
		// first get the QGP badge number
		if ($qgp_badge = Badge::where('name', 'QGP')->first())
		{
			// then delete all badges
			BadgeMember::where('badge_id', $qgp_badge->id)->delete();
			// then delete the badge itself, as it's a rating rather than a badge
			Badge::where('id', $qgp_badge->id)->delete();
		}

		// add an index to the member_rating table, as we'll be joining onto it a lot
		Schema::table('rating_member', function ($table) {
			$table->index('member_id');
		});

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

		// delete all automatically imported ratings
		$xcp = Rating::where('name', "X-Country Pilot")->first();
		$qgp = Rating::where('name', "QGP")->first();
		$sop = Rating::where('name', "Solo Pilot")->first();
		$srp = Rating::where('name', "Soaring Pilot")->first();
		$tp = Rating::where('name', "Task Pilot")->first();
		$ap = Rating::where('name', "Alpine Pilot")->first();

		RatingMember::where('rating_id', $xcp->id)->delete();
		RatingMember::where('rating_id', $qgp->id)->delete();
		RatingMember::where('rating_id', $sop->id)->delete();
		RatingMember::where('rating_id', $srp->id)->delete();
		RatingMember::where('rating_id', $tp->id)->delete();
		RatingMember::where('rating_id', $ap->id)->delete();

		// remove index
		Schema::table('rating_member', function ($table) {
			$table->dropIndex(['member_id']);
		});

		Schema::table('ratings', function ($table) {
			$table->dropColumn('archived');
			$table->dropColumn('numbered');
		});
	}
}
