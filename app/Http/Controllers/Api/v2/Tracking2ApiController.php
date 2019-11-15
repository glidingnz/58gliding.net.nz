<?php

namespace App\Http\Controllers\Api\V2;

use Illuminate\Http\Request;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests;
use App\Models\TrackingDay;
use App\Models\Aircraft;
use App\Models\Ping;
use Illuminate\Support\Facades\DB;
use Schema;
use DateTime;
use DateTimeZone;
use Log;

/**
 * Types of entries
 * 1) Flarm
 * 2) SPOT (US)
 * 3) Particle Cell Tracker
 * 4) Overland iOS App
 * 5) SPOT (NZ)
 * 6) InReach (NZ)
 * 7) btraced mobile app
 * 8) manual insertion API
 */

class Tracking2ApiController extends ApiController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	// dev local feed
	//var $url="http://spots.dev/FEED_ID_HERE/feed.json";
	var $url="https://api.findmespot.com/spot-main-web/consumer/rest-api/2.0/public/feed/FEED_ID_HERE/message.json";

	/**
	 * Get a list of aircraft that have tracking today
	 */
	
	public function aircraft($dayDate, $points=5)
	{
		$points = (int)$points; // ensure $pointsPerHex is an integer
		if (!$table_name = $this->_get_table_name($dayDate)) return $this->error(); 
		if (!Schema::connection('ogn')->hasTable($table_name)) return $this->not_found("Day Not Found");

		$aircraft = [];
		$hexes_to_load = [];
		$regos_to_load = [];

		// fetch all individual aircraft
		$results = DB::connection('ogn')->select('select distinct rego, hex FROM `'.$table_name.'`');
		foreach ($results as $key=>$result)
		{
			// work out the key for this aircraft
			if ($result->rego!=null) $key = $result->rego;
			else $key = $result->hex;

			$aircraft[$key] = $result;
			$aircraft[$key]->key = $key;

			// add to the list of aircraft to search for
			if ($result->rego!=null) $regos_to_load[] = 'ZK-' . $result->rego;
			if ($result->hex!=null) $hexes_to_load[] = $result->hex;
		}


		// load all aircraft from their hexes
		if (count($hexes_to_load)>0) {
			if ($aircraft_details = Aircraft::select('id', 'rego', 'contest_id', 'model', 'class', 'towplane', 'flarm', 'spot_esn')->whereIn('flarm', $hexes_to_load)->orWhereIn('rego', $regos_to_load)->get())
			{
				foreach ($aircraft_details AS $aircraft_detail)
				{
					foreach ($aircraft AS $key=>$craft)
					{
						// check for matching hex keys
						if ($aircraft_detail->flarm==$craft->hex && $craft->hex!='') {
							$aircraft[$key]->aircraft = $aircraft_detail;
						}
						// check for matching regos
						if ($aircraft_detail->rego=='ZK-' . $craft->rego && $craft->rego!=null) {
							$aircraft[$key]->aircraft = $aircraft_detail;
						}
					}

				}
			}
		}

		// load the data trail for each

		// check if we have the vspeed column
		$select_columns='';
		if(Schema::connection('ogn')->hasColumn($table_name, 'vspeed')) {
			$select_columns = ', vspeed';
		}
		$keys = [];
		$queries = [];

		foreach ($aircraft AS $key=>$craft)
		{
			if ($craft->rego!=null)
			{
				// then craft a query to get latest x points 
				$keys[]=$craft->rego;
				$queries[] = "(SELECT '".$craft->key."' AS thekey , id, thetime, X(loc) AS lat, Y(loc) AS lng, REPLACE(rego, 'ZK-', '') AS hex, alt, speed, course, rego, type".$select_columns." FROM `".$table_name."` WHERE rego=? ORDER BY thetime DESC LIMIT " . $points . ")";
			}
			else
			{
				// then craft a query to get latest x points 
				$keys[]=$craft->hex;
				$queries[] = "(SELECT '".$craft->key."' AS thekey , id, thetime, X(loc) AS lat, Y(loc) AS lng, REPLACE(rego, 'ZK-', '') AS hex, alt, speed, course, rego, type".$select_columns." FROM `".$table_name."` WHERE hex=? ORDER BY thetime DESC LIMIT " . $points . ")";
			}
		}

		if ($queries)
		{
			if ($pings = DB::connection('ogn')->select(implode($queries, ' UNION ALL '), $keys))
			{

				// walk through all pings backwards and work out course directions from previous points
				$index = count($pings);
				while($index) {
					$ping = $pings[--$index];
					// work out the direction from previous point, if it exists AND if we don't have direction from the GPS for this point.
					if (isset($previous_point[$ping->thekey]) && $ping->course==null) {

						$ping->course = $this->angle(
							$previous_point[$ping->thekey]->lat,
							$previous_point[$ping->thekey]->lng,
							$ping->lat,
							$ping->lng);
					}
					$previous_point[$ping->thekey] = $ping;
				}


				// go through the correct direction and add to the list of points
				foreach($pings AS $ping)
				{
					// round the lat and long to 6 digits, so we don't transmit unnecessary data
					$ping->lat = round($ping->lat, 6);
					$ping->lng = round($ping->lng, 6);

					$aircraft[$ping->thekey]->points[] = $ping;

					// remove the key to save data transfer, as we already have it as the index key
					unset($ping->thekey);
				}
			}
		}

		// strip out the array keys for javascript
		return $this->success(array_values($aircraft));
	}

	protected function _get_table_name($dayDate)
	{
		if ($dayDate==null || $dayDate=='' || $dayDate=='null') return false;
		$date = new \DateTime($dayDate);
		$table_name = 'data' . $date->format('Ymd');
		return $table_name;
	}

	/**
	 * Calculate angle between 2 given latLng
	 * @param  float $lat1
	 * @param  float $lat2
	 * @param  float $lng1
	 * @param  float $lng2
	 * @return integer
	 */
	function angle($lat1, $lng1, $lat2, $lng2) {
		$dLon = $lng2 - $lng1;
		$y = sin($dLon) * cos($lat2);
		$x = cos($lat1) * sin($lat2) - sin($lat1) * cos($lat2) * cos($dLon);
		return 360 - ((rad2deg(atan2($y, $x)) + 360) % 360);
	}
}