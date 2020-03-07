<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Membertype;
use App\Models\Position;
use App\Models\Org;

class Membersv2 extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Table to link a member with an organisation
		if (!Schema::hasTable('member_org')) {
			Schema::create('member_org', function (Blueprint $table) {
				$table->increments('id');
				$table->integer('member_id');
				$table->integer('membertype_id');
				$table->integer('org_id');
				$table->date('join_date')->nullable();
				$table->date('end_date')->nullable();
				$table->text('resigned_comment')->nullable();
				$table->timestamps();
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


		// get all members
		$members = DB::table('gnz_member')->get();
		foreach ($members AS $member)
		{
			if ($member->club!=null && $member->club!='')
			{
				if (isset($orgs_gnz_code[$member->club]))
				{
					$member->orgs()->save($orgs_gnz_code[$member->club]);
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
		Schema::drop('member_org');
		Schema::drop('membertypes');
		Schema::drop('member_membertype');
		Schema::drop('positions');
		Schema::drop('member_position');
	}
}
