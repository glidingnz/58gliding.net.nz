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
use SRTMGeoTIFFReader;

include(app_path() . '/Classes/SRTMGeoTIFFReader.php');

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


	// List of colours to use 
	var $colours = ['e86666', 'ab4b4b', 'e87766', 'ba6052', '8c483e', 'e88966', 'ba6e52', '8c533e', 'e89a66', 'ab714b', 'c99559', '9c7344', 'c9a459', '8c723e', 'e8ce66', '9c8a44', 'aba44b', 'dfe866', 'b3ba52', '878c3e', 'b0d95f', '8aba52', '739c44', '9ae866', '68c959', '58ab4b', '66e866', '3e8c3e', '66e889', '4bab65', '3e8c5d', '66e8ab', '52ba8a', '5fd9b0', '449c7f', '59c9b3', '66e8df', '52bab3', '3e8c87', '5fd1d9', '4ba4ab', '66cee8', '52a5ba', '3e7d8c', '5fb0d9', '4b8bab', '3e728c', '66abe8', '44739c', '669ae8', '44679c', '6689e8', '526eba', '3e538c', '6677e8', '44509c', '6052ba', '483e8c', '805fd9', '67449c', 'ab66e8', '8a52ba', 'ce66e8', 'ab4ba4', '8c3e87', 'e866ce', 'c959a4', '9c447f', 'e8669a', 'ba527c', 'e86689', '9c445c', 'e86677', 'ab4b58'];




	/**
	 * Get a list of aircraft that have tracking today
	 */
	public function points($dayDate, $points=5)
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
			$aircraft[$key]->colour = $this->colours[crc32($key) % count($this->colours)];

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

		// de-duplicate any aircraft into a new array:
		$unique_aircraft = [];
		// first ensure all aircraft have their rego
		foreach ($aircraft AS $key=>$craft) {
			if ($craft->rego==null && isset($craft->aircraft) && isset($craft->aircraft->rego)) {
				$aircraft[$key]->rego = substr($craft->aircraft->rego, 3, 3);
			}
		}
		// then create an array of aircraft again from that list
		foreach ($aircraft AS $key=>$craft) {
			if ($craft->rego!=null) $new_key = $craft->rego;
			else $new_key = $craft->hex;
			$craft->key = $new_key;
			$unique_aircraft[$new_key] = $craft;
		}



		// load the data trail for each

		// check if we have the vspeed column
		$select_columns='';
		if(Schema::connection('ogn')->hasColumn($table_name, 'vspeed')) {
			$select_columns = ', vspeed';
		}
		$keys = [];
		$queries = [];

		foreach ($unique_aircraft AS $key=>$craft)
		{
			$query = "(SELECT '".$craft->key."' AS thekey , id, thetime, X(loc) AS lat, Y(loc) AS lng, REPLACE(rego, 'ZK-', '') AS hex, alt, speed, course, rego, type".$select_columns." FROM `".$table_name."` WHERE 1=0";
			if ($craft->rego!=null) {
				$query .= " OR rego=?";
				$keys[]=$craft->rego;
			}
			if ($craft->hex!=null) {
				$query .= " OR hex=?";
				$keys[]=$craft->hex;
			}

			$query .= " ORDER BY thetime DESC LIMIT " . $points . ")";
			$queries[] = $query;
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
					$ping->gl = $this->_get_ground_level($ping->lat, $ping->lng);

					$unique_aircraft[$ping->thekey]->points[] = $ping;

					// remove the key to save data transfer, as we already have it as the index key
					unset($ping->thekey);
				}
			}
		}

		// strip out the array keys for javascript
		//return $this->success($unique_aircraft);
		return $this->success(array_values($unique_aircraft));
	}


	public function aircraft(Request $request, $dayDate, $key)
	{
		if (!$table_name = $this->_get_table_name($dayDate)) return $this->error(); 
		if (!Schema::connection('ogn')->hasTable($table_name)) return $this->not_found("Day Not Found");

		$rego = $hex = $key;

		// see if the key is a hex that belongs to an aircraft (i.e. flarm)
		if ($aircraft = Aircraft::where('rego', 'ZK-'.$key)->first())
		{
			$rego = substr($aircraft->rego, 3, 6); // strip the ZK-
			if ($aircraft->flarm!=null) $hex = $aircraft->flarm;
		}

		if ($aircraft = Aircraft::where('flarm', $key)->first())
		{
			$rego = substr($aircraft->rego, 3, 6); // strip the ZK-
			if ($aircraft->flarm!=null) $hex = $aircraft->flarm;
		}

		$select_columns='';
		if(Schema::connection('ogn')->hasColumn($table_name, 'vspeed')) {
			$select_columns = ', vspeed';
		}

		$query = "SELECT '".$key."' AS thekey , id, thetime, X(loc) AS lat, Y(loc) AS lng, hex, alt, speed, course, rego, type".$select_columns." FROM `".$table_name."` WHERE";
		$query .= " (rego=?";
		$keys[]=$rego;
		$query .= " OR hex=?)";
		$keys[]=$hex;

		if ($request->has('from')) {
			$query .= " AND id>?";
			$keys[]=$request->input('from');
		}


		$query .= " ORDER BY thetime DESC";
		$points = DB::connection('ogn')->select($query, $keys);

		if ($points)
		{
			return $this->success($points);
		}

		return $this->success(Array());
		
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


	protected function _get_ground_level($lat, $long)
	{
		try
		{
			$dataReader = new SRTMGeoTIFFReader(storage_path() . '/app/geotiffdata');
			$dataReader->showErrors = false;
			if ($elevation = $dataReader->getElevation($lat, $long))
			{
				if ($elevation<0) return null;
				return $elevation;
			}
		}
		catch(\Exception $e)
		{
			return null;
		}
		

		return null;
	}

}