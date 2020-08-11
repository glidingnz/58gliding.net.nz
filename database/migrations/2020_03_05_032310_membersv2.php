<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Membertype;
use App\Models\Position;
use App\Models\Org;
use App\Models\Affiliate;

class Membersv2 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		// Add ability to identify defunct organisations
		
		Schema::table('orgs', function (Blueprint $table) {
			$table->boolean('active')->default(true);
			$table->string('twitter')->nullable();
			$table->string('facebook')->nullable();
			$table->integer('waypoint_id')->nullable();
			$table->text('description')->nullable();
		});

		// insert defunct orgs
		if (!Org::where('gnz_code','gsl')->exists())
		{
			$neworg = new Org;
			$neworg->name = "Gliding South";
			$neworg->short_name = "South";
			$neworg->slug = "south";
			$neworg->website = "";
			$neworg->gnz_code = "gsl";
			$neworg->type = "club";
			$neworg->active = false;
			$neworg->save();
		}

		if (!Org::where('gnz_code','whg')->exists())
		{
			$neworg = new Org;
			$neworg->name = "Whangarei";
			$neworg->short_name = "Whangarei";
			$neworg->slug = "whangarei";
			$neworg->website = "";
			$neworg->gnz_code = "whg";
			$neworg->type = "club";
			$neworg->active = false;
			$neworg->save();
		}
		if (!Org::where('gnz_code','hac')->exists())
		{
			$neworg = new Org;
			$neworg->name = "Hauraki Aero Club";
			$neworg->short_name = "Hauraki";
			$neworg->slug = "hauraki";
			$neworg->website = "";
			$neworg->gnz_code = "hac";
			$neworg->type = "club";
			$neworg->active = false;
			$neworg->save();
		}
		if (!Org::where('gnz_code','wav')->exists())
		{
			$neworg = new Org;
			$neworg->name = "Wigram Aviation Sports";
			$neworg->short_name = "Wigram";
			$neworg->slug = "wigram";
			$neworg->website = "";
			$neworg->gnz_code = "wav";
			$neworg->type = "club";
			$neworg->active = false;
			$neworg->save();
		}
		if (!Org::where('gnz_code','not')->exists())
		{
			$neworg = new Org;
			$neworg->name = "North Otago";
			$neworg->short_name = "North Otago";
			$neworg->slug = "not";
			$neworg->website = "";
			$neworg->gnz_code = "not";
			$neworg->type = "club";
			$neworg->active = false;
			$neworg->save();
		}
		if (!Org::where('gnz_code','otg')->exists())
		{
			$neworg = new Org;
			$neworg->name = "Otago Gliding Club";
			$neworg->short_name = "Otago Gliding Club";
			$neworg->slug = "otg";
			$neworg->website = "";
			$neworg->gnz_code = "otg";
			$neworg->type = "club";
			$neworg->active = false;
			$neworg->save();
		}
		if (!Org::where('gnz_code','clv')->exists())
		{
			$neworg = new Org;
			$neworg->name = "Clutha Valley";
			$neworg->short_name = "Clutha Valley";
			$neworg->slug = "clutha";
			$neworg->website = "";
			$neworg->gnz_code = "clv";
			$neworg->type = "club";
			$neworg->active = false;
			$neworg->save();
		}
		if (!Org::where('gnz_code','ssg')->exists())
		{
			$neworg = new Org;
			$neworg->name = "Southern Soaring";
			$neworg->short_name = "Southern Soaring";
			$neworg->slug = "ssg";
			$neworg->website = "";
			$neworg->gnz_code = "ssg";
			$neworg->type = "club";
			$neworg->active = false;
			$neworg->save();
		}
		if (!Org::where('gnz_code','ras')->exists())
		{
			$neworg = new Org;
			$neworg->name = "Rangiora Advanced Soaring";
			$neworg->short_name = "Rangiora Advanced Soaring";
			$neworg->slug = "ras";
			$neworg->website = "";
			$neworg->gnz_code = "ras";
			$neworg->type = "club";
			$neworg->active = false;
			$neworg->save();
		}
		if (!Org::where('gnz_code','alp')->exists())
		{
			$neworg = new Org;
			$neworg->name = "Alpine Soaring";
			$neworg->short_name = "Alpine Soaring";
			$neworg->slug = "alp";
			$neworg->website = "";
			$neworg->gnz_code = "alp";
			$neworg->type = "club";
			$neworg->active = false;
			$neworg->save();
		}
		if (!Org::where('gnz_code','rga')->exists())
		{
			$neworg = new Org;
			$neworg->name = "Rangiora";
			$neworg->short_name = "Rangiora";
			$neworg->slug = "rangiora";
			$neworg->website = "";
			$neworg->gnz_code = "rga";
			$neworg->type = "club";
			$neworg->active = false;
			$neworg->save();
		}
		if (!Org::where('gnz_code','rpu')->exists())
		{
			$neworg = new Org;
			$neworg->name = "Ruapehu";
			$neworg->short_name = "Ruapehu";
			$neworg->slug = "ruapehu";
			$neworg->website = "";
			$neworg->gnz_code = "rpu";
			$neworg->type = "club";
			$neworg->active = false;
			$neworg->save();
		}
		if (!Org::where('gnz_code','epb')->exists())
		{
			$neworg = new Org;
			$neworg->name = "Eastern Bay of Plenty";
			$neworg->short_name = "Eastern Bay of Plenty";
			$neworg->slug = "epb";
			$neworg->website = "";
			$neworg->gnz_code = "epb";
			$neworg->type = "club";
			$neworg->active = false;
			$neworg->save();
		}
		if (!Org::where('gnz_code','sky')->exists())
		{
			$neworg = new Org;
			$neworg->name = "Sky Sailing";
			$neworg->short_name = "Sky Sailing";
			$neworg->slug = "sky";
			$neworg->website = "";
			$neworg->gnz_code = "sky";
			$neworg->type = "club";
			$neworg->active = false;
			$neworg->save();
		}


		// Table to link a member with an organisation, and track membership history
		if (!Schema::hasTable('affiliates')) {
			Schema::create('affiliates', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('member_id');
				$table->integer('org_id');
				$table->integer('membertype_id')->nullable();
				$table->date('join_date')->nullable();
				$table->date('end_date')->nullable();
				$table->text('resigned_comment')->nullable();
				$table->boolean('resigned')->default(false);
				$table->timestamps();
				$table->index('member_id', 'member_index');
			});
		}



		// membertype types table
		if (!Schema::hasTable('membertypes')) {
			Schema::create('membertypes', function (Blueprint $table) {
				$table->increments('id');
				$table->string('name');
				$table->string('slug');
				$table->integer('org_id')->nullable();
				$table->decimal('annual_fee')->nullable();
				$table->timestamps();
			});

			// get the GNZ org and setup the membertype types
			if ($gnz = Org::where('slug', 'gnz')->first())
			{
				$rating = new Membertype;
				$rating->name = "Flying";
				$rating->slug = "flying";
				$rating->org_id = $gnz->id;
				$rating->save();

				$rating = new Membertype;
				$rating->name = "Magazine only";
				$rating->slug = "mag-only";
				$rating->org_id = $gnz->id;
				$rating->save();

				$rating = new Membertype;
				$rating->name = "Flying Family";
				$rating->slug = "flying-family";
				$rating->org_id = $gnz->id;
				$rating->save();

				$rating = new Membertype;
				$rating->name = "Visiting Pilot 3 Month";
				$rating->slug = "visiting-3-month";
				$rating->org_id = $gnz->id;
				$rating->save();

				$rating = new Membertype;
				$rating->name = "Visiting Pilot Bulk";
				$rating->slug = "visiting-bulk";
				$rating->org_id = $gnz->id;
				$rating->save();

				$rating = new Membertype;
				$rating->name = "Junior Family";
				$rating->slug = "junior-family";
				$rating->org_id = $gnz->id;
				$rating->save();

				$rating = new Membertype;
				$rating->name = "Junior";
				$rating->slug = "junior";
				$rating->org_id = $gnz->id; 
				$rating->save();
			}
		}
 

		// Member Club Positions e.g. Secratary, CFI etc
		if (!Schema::hasTable('member_membertype')) {
			Schema::create('member_membertype', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('member_id');
				$table->integer('membertype_id');
				$table->timestamps();
			});
		}

		// Member Club Positions e.g. Secratary, CFI etc
		if (!Schema::hasTable('positions')) {
			Schema::create('positions', function (Blueprint $table) {
				$table->increments('id');
				$table->string('name');
				$table->string('abbreviation');
				$table->string('slug');
				$table->timestamps();
			});

			$rating = new Position;
			$rating->name = "President";
			$rating->abbreviation = "President";
			$rating->slug = "president";
			$rating->save();

			$rating = new Position;
			$rating->name = "Vice President";
			$rating->abbreviation = "Vice President";
			$rating->slug = "vice-president";
			$rating->save();

			// insert basic positions
			$rating = new Position;
			$rating->name = "Chief Flying Instructor";
			$rating->abbreviation = "CFI";
			$rating->slug = "cfi";
			$rating->save();

			$rating = new Position;
			$rating->name = "Chief Tow Pilot";
			$rating->abbreviation = "CTP";
			$rating->slug = "ctp";
			$rating->save();

			$rating = new Position;
			$rating->name = "Secratary";
			$rating->abbreviation = "secratary";
			$rating->slug = "secratary";
			$rating->save();

			$rating = new Position;
			$rating->name = "Treasurer";
			$rating->abbreviation = "Treasurer";
			$rating->slug = "treasurer";
			$rating->save();

			$rating = new Position;
			$rating->name = "Awards Officer";
			$rating->abbreviation = "Awards Officer";
			$rating->slug = "awards-officer";
			$rating->save();

			$rating = new Position;
			$rating->name = "Committee Member";
			$rating->abbreviation = "Committee";
			$rating->slug = "committee";
			$rating->save();
		}

		// join member positions
		if (!Schema::hasTable('member_position')) {
			Schema::create('member_position', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('member_id');
				$table->integer('position_id');
				$table->timestamps();
			});
		}



		// do the migration of the data
		// 
		// get the list or orgs
		$orgs = DB::table('orgs')->get();
		foreach ($orgs AS $key=>$org)
		{
			$orgs_gnz_code[$org->gnz_code] = $org;
		}

		// get all members, and store club details
		$members = DB::table('gnz_member')->get();
		foreach ($members AS $member)
		{
			// handle previous clubs
			if ($member->previous_clubs!=null && $member->previous_clubs!='')
			{
				$prev_clubs = explode(' ', $member->previous_clubs);
				foreach ($prev_clubs AS $prev_club)
				{

					// convert other names to the correct code
					switch($prev_club)
					{
						case 'Tauranga Gliding Club': 
						case 'Tauranga': 
							$prev_club='TGA'; break;
						case 'Auckland Gliding Club':
						case 'Auckland':
							$prev_club='AKL'; break;
						case 'Wellington Gliding Club':
						case 'Wellington':
							$prev_club='WLN'; break;
						case 'Piako Gliding Club':
						case 'Piako':
						case 'PkoPKO':
							$prev_club='PKO'; break;
						case 'South Canterbury Gliding Club':
							$prev_club='SCY'; break;
						case 'Nelson Lakes Gliding Club':
							$prev_club='NLN'; break;
						case 'Canterbury Gliding':
							$prev_club='CTY'; break;
						case 'Taupo Gliding Club':
						case 'TpoTPO ':
							$prev_club='TPO'; break;
						case 'AklAKL  ':
							$prev_club='AKL'; break;
						case 'Omarama Gliding Club  ':
							$prev_club='OGC'; break;
						case 'GSLOTG':
							$prev_club='GSL'; break;
						case 'Waipukurau Gliding Club':
						case 'Waipukurau Gliding ClubHBY':
							$prev_club='GSL'; break;
						case 'Marlborough Gliding Club':
							$prev_club='MLB'; break;
						case 'South Canterbury Gliding Clu':
							$prev_club='SCY'; break;
						case 'GomGOM ':
							$prev_club='GOM'; break;
						case 'Gliding Manawatu':
							$prev_club='WGM'; break;
						case 'Gliding Wairarapa':
							$prev_club='GWR'; break;
						case 'Hawkes Bay Gliding Club':
						case 'Waipukurau Gliding Club/ Hawkes Bay Gliding Club':
							$prev_club='HBY'; break;
						case 'Auckland Aviation Sports Club':
							$prev_club='AAV'; break;
						case 'Norfolk Aviation Sports Club':
							$prev_club='AAV'; break;
					}


					if (isset($orgs_gnz_code[$prev_club]))
					{
						$affiliate = new Affiliate();
						$affiliate->org_id = $orgs_gnz_code[$prev_club]->id;
						$affiliate->member_id = $member->id;
						$affiliate->resigned = true; // previous clubs are always resigned?

						// figure out if the resigned date applies
						if ($member->membership_type!=='resigned' && sizeof($prev_clubs)==1)
						{
							// if there are multiple previous clubs, then we have no way of knowing which one had the expiry date. So only do this if there is only 1 previous club.
							$affiliate->end_date = $member->resigned;
						}

						$affiliate->save();
					}
				}
			}

			// handle current club
			if ($member->club!=null && $member->club!='')
			{
				if (isset($orgs_gnz_code[$member->club]))
				{
					$affiliate = new Affiliate();
					$affiliate->org_id = $orgs_gnz_code[$member->club]->id;
					$affiliate->member_id = $member->id;
					$affiliate->join_date = $member->date_joined;
					$affiliate->end_date = $member->resigned;
					$affiliate->resigned_comment = $member->resigned_comment;

					if ($member->membership_type=='Resigned') $affiliate->resigned=true;

					$affiliate->save();
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
		Schema::drop('affiliates');
		Schema::drop('membertypes');
		Schema::drop('member_membertype');
		Schema::drop('positions');
		Schema::drop('member_position');
		
		Org::where('gnz_code','gsl')->delete();
		Org::where('gnz_code','whg')->delete();
		Org::where('gnz_code','hac')->delete();
		Org::where('gnz_code','wav')->delete();
		Org::where('gnz_code','not')->delete();
		Org::where('gnz_code','otg')->delete();
		Org::where('gnz_code','clv')->delete();
		Org::where('gnz_code','ssg')->delete();
		Org::where('gnz_code','ras')->delete();
		Org::where('gnz_code','alp')->delete();
		Org::where('gnz_code','rga')->delete();
		Org::where('gnz_code','rpu')->delete();
		Org::where('gnz_code','epb')->delete();
		Org::where('gnz_code','sky')->delete();


		Schema::table('orgs', function (Blueprint $table) {
			$table->dropColumn('active');
			$table->dropColumn('twitter');
			$table->dropColumn('facebook');
			$table->dropColumn('waypoint_id');
			$table->dropColumn('description');
		});

	}
}
