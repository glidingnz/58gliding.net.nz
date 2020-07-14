<?php
namespace App\Classes;

use App\Models\Member;
use App\Models\Org;
use App\Models\Rating;
use DateTime;
use DB;
use Gate;

class MemberUtilities {
	/**
	 * Generates a query for the filtered list of members
	 *
	 * @return Query
	 */
	public function get_filtered_members($request)
	{
		// first get the ids of the ratings
		$ratings = Rating::all();

		$query = Member::query();
		$query->orderBy('last_name');
		$query->selectRaw("gnz_member.*, 
			max(r_xcp.number) AS rating_xcp_number, 
			max(r_qgp.number) AS rating_qgp_number,
			max(IF(r_tow_pilot.id>0, true, false)) AS rating_tow_pilot,
			max(IF(r_instructor_a.id>0 OR r_instructor_b.id>0 OR r_instructor_c.id>0 OR r_instructor_d.id>0, true, false)) AS rating_instructor,
			max(IF(
				LEAST(IF(r_instructor_a.id>0, 'a', 'z'), IF(r_instructor_b.id>0, 'b', 'z'), IF(r_instructor_c.id>0, 'c', 'z'), IF(r_instructor_d.id>0, 'd', 'z'))<>'z'
				, LEAST(IF(r_instructor_a.id>0, 'a', 'z'), IF(r_instructor_b.id>0, 'b', 'z'), IF(r_instructor_c.id>0, 'c', 'z'), IF(r_instructor_d.id>0, 'd', 'z'))
				,null)) AS rating_instructor_level
			");

		if ($request->input('search'))
		{
			$s = '%' . $request->input('search') .'%';
			$query->where(function($query) use ($s) {
				$query->where('first_name','like',$s);
				$query->orWhere('last_name','like',$s);
				$query->orWhere('nzga_number','like',$s);
				$query->orWhere('club','like',$s);
			});
		}

		// join on the common ratings we need for sorting and display
		foreach ($ratings AS $rating)
		{
			switch ($rating->name)
			{
				case 'X-Country Pilot': 
					$query->leftJoin('rating_member AS r_xcp', function ($join) use ($rating) {
						$join->on('gnz_member.id', '=', 'r_xcp.member_id')
							->on('r_xcp.rating_id', '=', DB::raw($rating->id))
							->on(DB::raw("(r_xcp.expires>now() OR r_xcp.expires IS NULL) "), DB::raw('<>'), DB::raw('0'));
					});
					break;
				case 'QGP': 
					$query->leftJoin('rating_member AS r_qgp', function ($join) use ($rating) {
						$join->on('gnz_member.id', '=', 'r_qgp.member_id')
							->on('r_qgp.rating_id', '=', DB::raw($rating->id))
							->on(DB::raw("(r_qgp.expires>now() OR r_qgp.expires IS NULL)"), DB::raw('<>'), DB::raw('0'));
					});
					break;
				case 'Tow Pilot': 
					$query->leftJoin('rating_member AS r_tow_pilot', function ($join) use ($rating) {
						$join->on('gnz_member.id', '=', 'r_tow_pilot.member_id')
							->on('r_tow_pilot.rating_id', '=', DB::raw($rating->id))
							->on(DB::raw("(r_tow_pilot.expires>now() OR r_tow_pilot.expires IS NULL) "), DB::raw('<>'), DB::raw('0'));
					});
					break;
				case 'Instructor A Cat': 
					$query->leftJoin('rating_member AS r_instructor_a', function ($join) use ($rating) {
						$join->on('gnz_member.id', '=', 'r_instructor_a.member_id')
							->on('r_instructor_a.rating_id', '=', DB::raw($rating->id))
							->on(DB::raw("(r_instructor_a.expires>now() OR r_instructor_a.expires IS NULL) "), DB::raw('<>'), DB::raw('0'));
					});
					break;
				case 'Instructor B Cat': 
					$query->leftJoin('rating_member AS r_instructor_b', function ($join) use ($rating) {
						$join->on('gnz_member.id', '=', 'r_instructor_b.member_id')
							->on('r_instructor_b.rating_id', '=', DB::raw($rating->id))
							->on(DB::raw("(r_instructor_b.expires>now() OR r_instructor_b.expires IS NULL) "), DB::raw('<>'), DB::raw('0'));
					});
					break;
				case 'Instructor C Cat': 
					$query->leftJoin('rating_member AS r_instructor_c', function ($join) use ($rating) {
						$join->on('gnz_member.id', '=', 'r_instructor_c.member_id')
							->on('r_instructor_c.rating_id', '=', DB::raw($rating->id))
							->on(DB::raw("(r_instructor_c.expires>now() OR r_instructor_c.expires IS NULL) "), DB::raw('<>'), DB::raw('0'));
					});
					break;
				case 'Instructor D Cat': 
					$query->leftJoin('rating_member AS r_instructor_d', function ($join) use ($rating) {
						$join->on('gnz_member.id', '=', 'r_instructor_d.member_id')
							->on('r_instructor_d.rating_id', '=', DB::raw($rating->id))
							->on(DB::raw("(r_instructor_d.expires>now() OR r_instructor_d.expires IS NULL) "), DB::raw('<>'), DB::raw('0'));
					});
					break;
			}
		}



		if (!$request->input('resigned'))
		{
			$query->where(function($query) {
				$query->where('membership_type','!=','Resigned');
			});
		}


		// see if we are given an ORG ID e.g. 14
		if ($request->has('org_id'))
		{
			$org = Org::where('id', $request->input('org_id'))->first();
			$org_gnz_code = $org->gnz_code;
		}

		// see if we have an ORG code e.g. "PKO"
		if ($request->input('org') && $request->input('org')!='null')
		{
			$org_gnz_code = $request->input('org');
		}

		// process either of the two above
		if (isset($org_gnz_code))
		{
			switch ($org_gnz_code)
			{
				case 'MSC':
					$query->whereIn('club',['AKL','AAV','PKO','TPO','TGA','TRK']);
					break;
				case 'GSC':
					$query->whereIn('club',['GWR','WLN']);
					break;
				case 'OSC':
					$query->whereIn('club',['MLB','WLN', 'NLN', 'CTY', 'COT', 'SCY', 'OGC', 'CLV']);
					break;
				default:
					// for most normal clubs
					$query->where('club','=',$org_gnz_code);
					break;
			}

		}

		switch ($request->input('type'))
		{
			case 'glider-pilot':
				$query->where(function($query) {
					$query->where('membership_type','=','Flying');
				});
				break;
			case 'instructors':
				$query->havingRaw('rating_instructor=true');
				break;
			case 'tow-pilots':
				$query->havingRaw('rating_tow_pilot=true');
				break;
			case 'qgp':
				$query->having('rating_qgp_number','>','0');
				break;
			case 'xcp':
				$query->having('rating_xcp_number','>','0');
				break;
			case 'coaches':
				$query->where(function($query) {
					$query->where('coach','=','1');
				});
				break;
			case 'contest_pilots':
				$query->where(function($query) {
					$query->where('contest_pilot','=','1');
				});
				break;
			// case 'students':
			// // case 'non-qgp':
			// 	$query->where(function($query) {
			// 		$query->where('qgp_number','=','NULL');
			// 		$query->orWhere('qgp_number','=','0');
			// 	});
			// 	$query->where('tow_pilot','!=','1');
			// 	$query->where('membership_type','!=','Mag Only');
			// 	break;
			case 'oo':
				$query->where('observer_number','!=','NULL');
				$query->where('observer_number','!=','');
				$query->where('observer_number','!=','0');
				break;
			case 'youth':
				// check which month we are in to decide which year we need
				if (date("n")<=10)
				{
					// less or equal to October, so use this year
					$theyear=date("Y");
				}
				else
				{
					// after october, so use next year
					$theyear=date("Y") + 1;
				}
				$thirty_first_of_oct =  $theyear . '-10-31';
				$query->whereRaw('DATE_FORMAT(FROM_DAYS(DATEDIFF("'.$thirty_first_of_oct.'", date_of_birth)),"%Y")+0 BETWEEN 1 AND 26');

				$query->where('membership_type','!=','Mag Only');
				break;
		}

		// group by member
		$query->groupBy('gnz_member.id');

		return $query;
	}


	// filter out any columns that shouldn't be displayed unless you have permission
	public function filter_view_results(&$members)
	{
		// Remove any sensitve data if privacy flag set on this user
		if($members instanceof \Traversable) {
			$iteratable_members = $members;
		} else {
			$iteratable_members[] = $members;
		}
		
		foreach ($iteratable_members as $member) {
			$this->filter_view_result($member);
		}
	}


	public function filter_view_result(&$member)
	{
		// if you can edit this (i.e. yourself or you're the members club admin) allow viewing
		if (Gate::allows('edit-member', $member)) {
			return true;
		}

		if (Gate::allows('membership-view')) {
			return true;
		}

		if (Gate::denies('club-admin')) {
			$member->date_of_birth=null;
			$member->access_level=null;
			$member->comments=null;
			$member->address_1=null;
			$member->address_2=null;

			if ($member->privacy) {
				$member->email = null;
				$member->city = null;
				$member->country = null;
				$member->home_phone = null;
				$member->mobile_phone = null;
				$member->business_phone = null;
			}
		}
	}

	/**
	 * Get a list of members who have changed date
	 * Optionally pass a date. If not provided use today.
	 */
	public function get_address_changes($limit_date=null)
	{

		// An array to store the users with addresses that have changed
		$users = Array();

		if (DateTime::createFromFormat('Y-m-d', $limit_date))
		{
			$limiting_date = new DateTime($limit_date);
			$date_limit_sql="'".$limiting_date->format("Y-m-d") . "'";
		} else {
			// no  valid date given means use today
			$date_limit_sql="CURDATE()";
		}

		// find all address changes for today
		$results = DB::select( DB::raw("SELECT * FROM gnz_changelog WHERE field IN('address_1','address_2','city','country','zip_post') AND DATE(`created`)=" . $date_limit_sql));

		// compile the list of users
		foreach ($results AS $result)
		{
			// get the member details if we haven't yet. So we have the full address.
			if (!isset($users[$result->id_member])) {
				$member = Member::select('id', 'nzga_number', 'first_name', 'last_name', 'email', 'address_1', 'address_2', 'city' , 'country', 'zip_post')->where('id', $result->id_member)->first();
				$users[$result->id_member] = $member->toArray();
			}

			// Store the changes as well
			$users[$result->id_member]['changes'][$result->field]['old'] = $result->oldval;
			$users[$result->id_member]['changes'][$result->field]['new'] = $result->newval;
		}
		return $users;
	}

}