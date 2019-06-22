<?php
namespace App\Classes;

use App\Models\Member;
use DateTime;
use DB;

class MemberUtilities {

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