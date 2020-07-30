<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests;
use App\Models\TrackingDay;
use App\Models\Aircraft;
use App\Models\Ping;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
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
 * 9) MT600 Chinese tracker
 * 10) InReach (US)
 */

class TrackingApiController extends ApiController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	// dev local feed
	//var $url="http://spots.dev/FEED_ID_HERE/feed.json";
	var $url="https://api.findmespot.com/spot-main-web/consumer/rest-api/2.0/public/feed/FEED_ID_HERE/message.json";
	var $inreach_url="https://inreach.garmin.com/feed/Share/FEED_ID_HERE";
	// var $inreach_url="http://58gliding.net.test/feed-4.kml"; // for testing


	/**
	 * Manual insertion API
	 * 
	 * {"aircraft":"ZK-GGR","type":8, "points": [{"utctime":1542638495,"lat":-41.2334745,"lon":174.348365,"alt":345.1,"speed":25,"course":180,"vspeed":5.3},{"utctime":1542638496,"lat":-41.2334343,"lon":174.32545,"alt":345.1,"speed":25,"course":180,"vspeed":5.3}]}
	 * 
	 * aircraft: can be "ZK-GGR" or "GGR" or the FLARM hex code of the aircraft. If using registration, it must be in the gliding.net.nz aircraft DB.
	 * type: should be 8 unless a specific custom type is used.
	 * points: an array of points, consisting of the following:
	 * 	utctime: unix timestamp
	 * 	lat/lon: decimal format
	 * 	alt: in meters
	 * 	speed: (optional) in km/h
	 * 	course: (optional) direciton or angle in degrees between 0 and 360
	 * 	vspeed: (optional) vertical speed in m/s. Recommend average from the last 10 seconds.
	 */
	public function insert(Request $request)
	{
		if (!$request->isMethod('post')) {
			$data = $request->json()->all();
		}
		else
		{
			$data = $request;
		}

		Log::info($data);

		// find the aircraft specified
		$queryAircraft = Aircraft::query();
		$aircraft = $queryAircraft->where('rego','=',$data->aircraft)->orWhere('rego','=','ZK-' .$data->aircraft)->orWhere('flarm','=',$data->aircraft)->first();

		if (!$aircraft) {
			return $this->error('Aircraft not found');
		}

		$hex = $aircraft->flarm;
		$type=8;
		if (isset($data->type) && is_numeric($data->type) && $data->type>=8) 
		{
			$type = $data->type;
		}

		foreach ($data->points AS $point)
		{
			// required data
			if (!isset($point['utctime'])) return $this->error('utctime not provided');
			if (!isset($point['lat'])) return $this->error('lat not provided');
			if (!isset($point['lon'])) return $this->error('lon not provided');
			if (!isset($point['alt'])) return $this->error('alt not provided');

			$lat = $point['lat'];
			$lon = $point['lon'];
			$alt = $point['alt'];
			
			// optional data
			$speed = isset($point['speed']) ? $point['speed'] : NULL;
			$vspeed = isset($point['vspeed']) ? $point['vspeed'] : NULL;
			$course = isset($point['course']) ? $point['course'] : NULL;

			$thetime = new DateTime();
			$thetime->setTimestamp($point['utctime']);
			$thetimestamp = $thetime->format("Y-m-d H:i:s");

			$nzdate = new DateTime();
			$nzdate->setTimestamp($point['utctime']);
			$nzdate->setTimezone(new DateTimeZone('Pacific/Auckland'));
			$table_name = 'data' . $nzdate->format('Ymd');

			if (!$this->check_table_exists($nzdate)) $this->make_table($nzdate);

			DB::connection('ogn')->insert('insert into '. $table_name .' (thetime, alt, loc, hex, speed, course, type, rego, vspeed) values (?, ?, POINT(?,?), ?, ?, ?, ?, ?, ?)', [$thetimestamp, $alt, $lat, $lon, $hex, $speed, $course, $type, substr($aircraft['rego'], 3,3), $vspeed]);
		}
		return $this->success();

	}


	/* Chinese tracker mt600

Example string:
861585042912483$211051.00,A,3744.2431,S,17544.3354,E,,153.28,69.8,311219

(
    [0] => 861585042912483$211051.00 	ID & time
    [1] => A 							GPS Status
    [2] => 3744.2431 					Latitude
    [3] => S 							
    [4] => 17544.3354 					Longitude
    [5] => E 		
    [6] => 								Speed Over Ground knots
    [7] => 153.28						Course Over Ground degrees
    [8] => 69.8							Altitude meters
    [9] => 311219						Date DDMMYY

	If nothing comes through we get:
	(
	  'data' => false,
	)
 */
	public function mt600(Request $request)
	{
		$request_data = $request->json()->all();
		Log::info($request_data);

		$data = explode(',', $request_data['data']);
		if (!isset($data[0])) return false;
		if (!isset($data[6])) return false; // ensure enough data came through. Checks for 'data false' state.

		// get the timestamp
		$first_chunk = explode('$', $data[0]);

		if (!isset($first_chunk[0])) return false;

		$output['imei'] = $first_chunk[0];

		// find the aircraft
		$aircraft = Aircraft::where('mt600','=',$output['imei'])->first();
		if (!$aircraft) $this->error('aircraft not found');


		$utc_time = $first_chunk[1];
		$lat = $data[2];
		$long = $data[4];
		$speed = $data[6]; 
		if ($speed) {
			$speed = $speed * 1.852; // convert knots to km/h
		}
		$course = $data[7];
		$alt = $data[8];
		$date = $data[9];

		// convert lat and long to decimal minutes
		$lat_degrees = substr($lat, 0, strlen($lat)-7);
		$long_degrees = substr($long, 0, strlen($long)-7);

		$lat_minutes = substr($lat, strlen($lat)-7);
		$long_minutes = substr($long, strlen($long)-7);
		
		$lat_decimal = $lat_degrees + ($lat_minutes/60);
		$long_decimal = $long_degrees + ($long_minutes/60);

		if ($data[3]=='S') $lat_decimal = -$lat_decimal;
		if ($data[5]=='W') $long_decimal = -$long_decimal;

		$lat = $lat_decimal;
		$long = $long_decimal;

		$year = '20' . substr($date, 4, 2);
		$month = substr($date, 2, 2);
		$day = substr($date, 0, 2);
		$hours = substr($utc_time, 0, 2);
		$mins = substr($utc_time, 2, 2);
		$secs = substr($utc_time, 4, 2);

		if ($aircraft['flarm']==null) $hex = $aircraft['rego'];
		else $hex = $aircraft['flarm'];

		$timestamp = $year . '-' . $month . '-' . $day . ' ' . $hours . ':' . $mins . ':' . $secs;

		// figure out table name
		$nzdate = DateTime::createFromFormat("Y-m-d H:i:s", $timestamp);
		$nzdate->setTimezone(new DateTimeZone('Pacific/Auckland')); // convert UTC to NZ time
		$table_name = 'data' . $nzdate->format('Ymd');
		
		if (!$this->check_table_exists($nzdate)) $this->make_table($nzdate);

		DB::connection('ogn')->insert('insert into '. $table_name .' (thetime, alt, loc, hex, speed, course, type, rego, vspeed) values (?, ?, POINT(?,?), ?, ?, ?, ?, ?, ?)', [$timestamp, $alt, $lat, $long, $hex, $speed, $course, 9, substr($aircraft['rego'], 3,3), null]);
		return $this->success();
	}


	// Particle.io electron data from the webhook
	public function electron(Request $request)
	{
		// split it up by commas
		// lat, long, altitude, speed

		// Example data structure from a Particle.io device
		// [2018-11-15 08:35:52] production.INFO: array (
		//   'event' => 'G',
		//   'data' => '-37.797298,175.301620,51,0',
		//   'published_at' => '2018-11-15T08:35:51.773Z',
		//   'coreid' => '29003700074XXXXXXXXXX',
		// )

		if (!$request->isMethod('post')) {
			$request_data = $request->json()->all();
		}
		else
		{
			$request_data = $request;
		}

		Log::info($request_data);

		// get aircraft details
		$queryAircraft = Aircraft::query();
		$queryAircraft->where('particle_id','=',$request_data['coreid']);

		if ($aircraft = $queryAircraft->first())
		{
			$data = explode(',', $request_data['data']);

			if (isset($data[4]))
			{
				// calculate the time from the time given e.g. 75549 = 20:59:09
				$hours = floor($data[4]/3600);
				$mins = floor($data[4]/60 % 60);
				$secs = floor($data[4] % 60);
				$timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);

				$gps_time = substr($request_data['published_at'], 0, 11) . $timeFormat . '.000Z';
			}
			else
			{
				$gps_time = $request_data['published_at'];
			}

			$thetime = new DateTime($gps_time);
			$thetimestamp = $thetime->format("Y-m-d H:i:s");

			$nzdate = new DateTime($gps_time);
			$nzdate->setTimezone(new DateTimeZone('Pacific/Auckland'));
			$table_name = 'data' . $nzdate->format('Ymd');

			// check the table exists, otherwise make it
			if (!$this->check_table_exists($nzdate)) $this->make_table($nzdate);

			// speed is in knots. So convert to km/h
			$speed_kmh = 1.852 * $data[3];
			// check if we have an angle
			if (isset($data[9])) $angle = $data[9];
			else $angle = NULL;

			// vertical speed from a particle device is distance in meters over the last 10 seconds. To be stored as meters per second.
			if (isset($data[10])) $vspeed = $data[10]/10;
			else $vspeed = NULL;

			DB::connection('ogn')->insert('insert into '. $table_name .' (thetime, alt, loc, hex, speed, course, type, rego, vspeed) values (?, ?, POINT(?,?), ?, ?, ?, ?, ?, ?)', [$thetimestamp, $data[2], $data[0], $data[1], $aircraft['flarm'], $speed_kmh, $angle, 3, substr($aircraft['rego'], 3,3), $vspeed]);

			return $this->success();
		} else {
			return $this->error('Aircraft not found');; 
		}
		return $this->error(); 
	}



	public function btraced(Request $request, $rego)
	{

		$rego = strtoupper($rego);

		// // get aircraft details
		$queryAircraft = Aircraft::query();
		$queryAircraft->where('rego','=','ZK-' . $rego);

		if ($aircraft = $queryAircraft->first())
		{

			$xml = simplexml_load_string($request->getContent());
			Log::info(print_r($xml, 1));
			
			if (!isset($xml->devId)) {
				Log::info("Couldn't find devId on btraced XML");
				Log::info(print_r($xml, 1));
				return null;
			}

			// Get device identification
			$deviceId = $xml->devId;
			
			// Prepare list of points
			$goodPointsList = "";

			// Start processing each travel
			foreach ($xml->travel as $travel) {
				
				// Get travel common information
				$travelId = $travel->id;
				$travelName = $travel->description;
				$travelLength = $travel->length;
				$travelTime = $travel->time;
				$travelTPoints = $travel->tpoints;
				
				// Prepare the succesful points
				$goodPointsList = '';
				
				// Process each point
				foreach ($travel->point as $point) {
					
					// Get all the information for this point
					$pointId = $point->id;
					$pointDate = date("Y-m-d H:i:s", trim($point->date));
					$pointLat = $point->lat;
					$pointLon = $point->lon;
					$pointSpeed = $point->speed * 3.6; // convert from m/s to km/h
					$pointCourse = $point->course;
					$pointHAccu = $point->haccu;
					$pointBatt = $point->bat;
					$pointVAccu = $point->vaccu;
					$pointAltitude = $point->altitude;
					$pointContinous = $point->continous;
					$pointTDist = $point->tdist;
					$pointRDist = $point->rdist;
					$pointTTime = $point->ttime;
					
					// check if we have a good fix. If alt is zero, probably not, and don't store the point.
					if ($pointAltitude!=0)
					{
						$nzdate = new DateTime();
						// $nzdate->setTimezone(new DateTimeZone('Pacific/Auckland')); // don't convert because the UNIX time coming in is already NZ time. Bad btraced.
						$nzdate->setTimestamp(trim($point->date));
						$table_name = 'data' . $nzdate->format('Ymd');
						
						// create the UTC time from the NZ time. Had to output and import again to loose the timezone, as the nzdate above is in the wrong timezone.
						$utcdate = DateTime::createFromFormat("Y-m-d H:i:s", $nzdate->format('Y-m-d H:i:s'), new DateTimeZone('Pacific/Auckland'));
						$utcdate->setTimezone(new DateTimeZone('UTC'));
						$thetimestamp = $utcdate->format("Y-m-d H:i:s");

						// check the table exists, otherwise make it
						if (!$this->check_table_exists($nzdate)) $this->make_table($nzdate);

						$hex = $aircraft['flarm'];
						if ($hex==null) $hex='';

						if (DB::connection('ogn')->insert('insert into '. $table_name .' (thetime, alt, loc, hex, speed, course, type, rego) values (?, ?, POINT(?,?), ?, ?, ?, ?, ?)', [$thetimestamp, $pointAltitude, $pointLat, $pointLon, $hex, $pointSpeed, $pointCourse, 7, $rego]))
						{
							$goodPointsList .= $pointId.",";
						}
					}
					else
					{
						// mark the point as OK anyway, we don't want btraced to try and resend bad points
						$goodPointsList .= $pointId.",";
					}
				}
			}

			// Check if there was points 
			if ($goodPointsList != "") {
				// Remove last comma
				$goodPointsList = substr($goodPointsList, 0, -1);
				// Send back the answer for the saved points
				echo '{"id":0, "tripid":'.$travelId.',"points":['.$goodPointsList.'],"valid":true}';
			} else {
				// Just OK, the code should never reach here as we always have points
				echo '{"id":0, "tripid":'.$travelId.',"valid":true}';
			}
		}
	}





	// SPOTNZ = type 5
	public function spotnz(Request $request)
	{
		Log::info($request->getContent());

		$data['messageId'] = 0;
		$data['response'] = 'OK';
		$data['error'] = null;

		if (isset($request['gatewayMessageId'])) {
			$data['messageId'] = $request['gatewayMessageId'];

			$queryAircraft = Aircraft::query();
			$queryAircraft->where('spot_esn','=',$request['deviceId']);

			if ($aircraft = $queryAircraft->first())
			{
				$rego = substr($aircraft['rego'], 3,3);
				$hex = $aircraft['flarm'];
			}
			else
			{
				// If we don't know the aircraft, store it anyway, using the device ID converted to hex as the hex code.
				$rego = null;
				$hex = null;
			}

			if ($hex==null) {
				$hex = strtoupper(dechex(substr($request['deviceId'], 2)));
			}

			$altitude=null;
			$speed=null;
			$course=null;
			$type=5;

			if ($request->exists('altitude')) $altitude=$request['altitude'];
			if ($request->exists('speed')) $speed=$request['speed'];
			if ($request->exists('direction')) $course=$request['direction'];
			if ($request->exists('deviceTypeCode')) {
				switch ($request['deviceTypeCode'])
				{
					case 'GIRS':
					case 'GIRE':
					case 'GIRM':
						$type=6;
						break;
				}
			}

			$thetime = new DateTime($request['deviceSendDateTime']);
			$thetimestamp = $thetime->format("Y-m-d H:i:s");

			$nzdate = new DateTime($request['deviceSendDateTime']);
			$nzdate->setTimezone(new DateTimeZone('Pacific/Auckland'));
			$table_name = 'data' . $nzdate->format('Ymd');
		
			// check the table exists, otherwise make it
			if (!$this->check_table_exists($nzdate)) $this->make_table($nzdate);

			if (!$request->exists('latitude') || $request['latitude']==0) {
				$data['response'] = 'Fail';
				$data['error'] = 'latitude not provided';
			}
			if (!$request->exists('longitude') || $request['longitude']==0) {
				$data['response'] = 'Fail';
				$data['error'] = 'longitude not provided';
			}

			if ($data['response']=='OK')
			{
				DB::connection('ogn')->insert('insert into '. $table_name .' (thetime, alt, loc, hex, speed, course, type, rego) values (?, ?, POINT(?,?), ?, ?, ?, ?, ?)', [$thetimestamp, $altitude, $request['latitude'], $request['longitude'], $hex, $speed, $course, $type, $rego]);
			}
			
		}
		else
		{
			$data['response'] = 'Fail';
			$data['error'] = 'gatewayMessageId not provided';
		}

		return response()->json($data);
	}




	public function overland(Request $request)
	{
		//Log::info($request);
		Log::info($request->getContent());

		$obj = json_decode($request->getContent());

		// check we have a result we are expecting
		if (isset($obj->locations[0]->properties->device_id))
		{

			// get the rego from the first point
			$rego = strtoupper($obj->locations[0]->properties->device_id);

			// // get aircraft details
			$queryAircraft = Aircraft::query();
			$queryAircraft->where('rego','=','ZK-' . $rego);

			if ($aircraft = $queryAircraft->first())
			{
				// loop through each data point
				foreach ($obj->locations AS $location)
				{


					if (isset($location->properties->altitude) && $location->properties->horizontal_accuracy<400)
					{
						$thetime = new DateTime($location->properties->timestamp);
						$thetimestamp = $thetime->format("Y-m-d H:i:s");

						$nzdate = new DateTime($location->properties->timestamp);
						$nzdate->setTimezone(new DateTimeZone('Pacific/Auckland'));
						$table_name = 'data' . $nzdate->format('Ymd');

						// check the table exists, otherwise make it
						if (!$this->check_table_exists($nzdate)) $this->make_table($nzdate);


						DB::connection('ogn')->insert('insert into '. $table_name .' (thetime, alt, loc, hex, speed, course, type, rego) values (?, ?, POINT(?,?), ?, ?, ?, ?, ?)', [
								$thetimestamp, 
								$location->properties->altitude, 
								$location->geometry->coordinates[1], 
								$location->geometry->coordinates[0], 
								$aircraft['flarm'], 
								$location->properties->speed * 3.6, // convert from m/s to km/h
								NULL, 
								4, 
								$rego
							]);
					}
				}
			}
		}

		$data['result']='ok';
		return response()->json($data);
		

	}



	public function check_table_exists($nzdate)
	{
		$tablename = 'data' . $nzdate->format('Ymd');
		return Schema::connection('ogn')->hasTable($tablename);
	}

	public function make_table($nzdate)
	{
		// WARNING this table is also created by the OGN sucker, i.e. the flarm capturing perl script.
		$tablename = 'data' . $nzdate->format('Ymd');
		$query = "CREATE TABLE IF NOT EXISTS " . $tablename . "(
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`thetime` datetime NOT NULL,
			`alt` int(11) DEFAULT NULL,
			`loc` point NOT NULL,
			`hex` char(6) NOT NULL DEFAULT '000000',
			`speed` smallint(6) DEFAULT NULL,
			`course` smallint(6) DEFAULT NULL,
			`type` tinyint(4) DEFAULT NULL,
			`rego` char(3) DEFAULT NULL,
			`vspeed` DECIMAL(6,2) DEFAULT NULL,
			PRIMARY KEY (`id`),
			KEY `dateindex` (`thetime`),
			KEY `hexindex2` (`hex`,`thetime`),
			KEY `regohexindex` (`rego`,`hex`,`thetime`),
			SPATIAL KEY `loc` (`loc`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		DB::connection('ogn')->statement($query);

		$query = "INSERT INTO days SET day_date={$nzdate->format('Ymd')}";
		DB::connection('ogn')->statement($query);
	}

	public function fetchInReach()
	{
		// Example feed
		// https://inreach.garmin.com/feed/Share/keithessex?d1=2017-07-15T00:01z
		// 
		// 
		// get the list of active InReaches

		Log::info('Fetching US InReach');

		$spots = Array();
		$data = Array();
		$queryAircraft = Aircraft::query();
		$queryAircraft->where('inreach_share','<>','');

		if ($aircrafts = $queryAircraft->get())
		{
			// fetch each inreach
			foreach ($aircrafts AS $aircraft)
			{
				$utc_date = new Carbon();
				$utc_date->subHours(24);
				$date_string = $utc_date->format('Y-m-d') . 'T00:00z';

				// create the correct URL for this aircraft
				$aircraft_url = str_replace('FEED_ID_HERE', $aircraft['inreach_share'], $this->inreach_url) . '?d1=' . $date_string . '&imei=' . $aircraft['inreach_imei'];

				Log::info($aircraft_url);
				// create UTC date

				if ($xml = file_get_contents($aircraft_url))
				{
					$obj = simplexml_load_string($xml);

					if ($obj === false) {
						Log::info("Failed loading XML: ");
						foreach(libxml_get_errors() as $error) {
							Log::info( $error->message);
						}
						continue;
					}

					if (isset($obj->Document->Folder)) {
						foreach ($obj->Document->Folder->Placemark AS $placemark)
						{
							foreach ($placemark->ExtendedData AS $point)
							{

								// the data we need to extract
								$thetimestamp = null;
								$lat = null;
								$lng = null;
								$alt = null;
								$speed = null;
								$course = null;

								// For each point, loop through the individual pieces of data
								foreach ($point->Data AS $data)
								{

									$param_name = '';
									foreach ($data->attributes() AS $name)
									{
										$param_name = $name;
									}

									switch($param_name)
									{
										case 'Time UTC': // e.g. 1/31/2020 12:08:30 AM
											echo $data->value . ' ';
											$thetime = Carbon::createFromFormat('n/j/Y h:i:s A', trim($data->value));
											//$thetime = Carbon::createFromFormat('n/j/Y h:i:s A', '1/31/2010 12:08:30 AM');
											$thetimestamp = $thetime->format('Y,m,d H:i:s');
											echo $thetimestamp . "<br>\n";
											break;
										case 'Latitude':
											$lat = $data->value;
											break;
										case 'Longitude': // e.g. 176.328700
											$lng = $data->value;
											break;
										case 'Elevation': // e.g. 2973.66 m from MSL
											$split_string = explode(' ', $data->value);
											$alt = $split_string[0];
											break;
										case 'Velocity': // e.g. 144.2 km/h
											$split_string = explode(' ', $data->value);
											$speed = $split_string[0];
											break;
										case 'Course': // e.g. 135.00 ° True
											$split_string = explode(' ', $data->value);
											$course = $split_string[0];
											break;
										default:
											break;
									}
								}
								
								// get the hex code for the aircraft
								$hex = $aircraft['flarm'];
								if ($hex==null) $hex=substr($aircraft['rego'], 3,3);

								// create the table name with the NZ timezone
								$thetime->setTimezone(new DateTimeZone('Pacific/Auckland'));
								$table_name = 'data' . $thetime->format('Ymd');
								if (!$this->check_table_exists($thetime)) $this->make_table($thetime);

								// check this hasn't been inserted yet
								$ping = DB::connection('ogn')->table($table_name)->where('thetime', $thetimestamp)->where('type', 10)->first();

								if (!$ping) {
									DB::connection('ogn')->insert('insert into '. $table_name .' (thetime, alt, loc, hex, speed, course, type, rego) values (?, ?, POINT(?,?), ?, ?, ?, ?, ?)', [$thetimestamp, $alt, $lat, $lng, $hex, $speed, $course, 10, substr($aircraft['rego'], 3,3)]);
								} 

							}
						}
					}
				}

				// wait for 3 seconds between each aircraft to not overload Garmin servers
				sleep(3);
			}
		}
		return $this->success($data, FALSE);
	}


	public function fetchSpots()
	{
		// get the list of active SPOTs
		$spots = Array();
		$data = Array();

		$queryAircraft = Aircraft::query();
		$queryAircraft->where('spot_feed_id','<>','');

		Log::info('Fetching US Spots');

		if ($aircrafts = $queryAircraft->get())
		{
			// fetch each spot
			foreach ($aircrafts AS $aircraft)
			{
				// create the correct URL for this aircraft
				$aircraft_url = str_replace('FEED_ID_HERE', $aircraft['spot_feed_id'], $this->url);
				

				if ($json = file_get_contents($aircraft_url))
				{
					$obj = json_decode($json);
					Log::info('SPOT JSON:');
					Log::info($json);

					// check if we have messages for this ID
					if (isset($obj->response->feedMessageResponse))
					{

						// Check if we only have one item. If so, it's an object, not an array.
						if (!is_array($obj->response->feedMessageResponse->messages->message)) {
							// Make it an array so it matches multiple items
							$obj->response->feedMessageResponse->messages->message = Array($obj->response->feedMessageResponse->messages->message);
						}

						// loop through them all
						foreach ($obj->response->feedMessageResponse->messages->message AS $point)
						{
							if (!isset($point->unixTime)) continue;
							
							$thetimestamp = date("Y-m-d H:i:s", $point->unixTime); // TOFIX
							$nzdate = new DateTime('@' . $point->unixTime);
							$nzdate->setTimezone(new DateTimeZone('Pacific/Auckland'));
							$table_name = 'data' . $nzdate->format('Ymd');


							// check the table exists, otherwise make it
							if (!$this->check_table_exists($nzdate)) $this->make_table($nzdate);

							$alt = null;
							if (isset($point->altitude) && $point->altitude>0) {
								$alt = $point->altitude;
							}

							$hex = $aircraft['flarm'];
							if ($hex==null) $hex=substr($aircraft['rego'], 3,3);

							$ping = DB::connection('ogn')->table($table_name)->where('thetime', $thetimestamp)->where('type', 2)->first();

							Log::info('Inserting ' . $hex);
							if (!$ping) {
								DB::connection('ogn')->insert('insert into '. $table_name .' (thetime, alt, loc, hex, speed, course, type, rego) values (?, ?, POINT(?,?), ?, ?, ?, ?, ?)', [$thetimestamp, $alt, $point->latitude, $point->longitude, $hex, NULL, NULL, 2, substr($aircraft['rego'], 3,3)]);
							} 
						}
					}

				}

				// wait for 3 seconds between each aircraft to not overload SPOT servers
				sleep(3);
			}
		}
		return $this->success($data, FALSE);
		//return $this->error(); 
	}


	public function days()
	{
		// work out what today is
		$nzdate = new DateTime();
		$nzdate->setTimezone(new DateTimeZone('Pacific/Auckland'));
		$today = $nzdate->format('Y-m-d');

		if ($days = TrackingDay::orderBy('day_date', 'DESC')->where('day_date', "<=", $today)->limit(14)->get())
		{
			return $this->success($days);
		}
		return $this->error(); 
	}

	public function dayHexes($date)
	{
		if (!$table_name = $this->_get_table_name($dayDate)) return $this->error(); 
		if (!Schema::connection('ogn')->hasTable($table_name)) return $this->not_found("Day Not Found");

		$hexes = DB::connection('ogn')->select('SELECT count(hex) AS hex_count, hex FROM `'.$table_name."` WHERE hex<>'' GROUP BY hex");

		if ($hexes)
		{
			return $this->success($hexes);
		}
		return $this->error(); 
	}


	public function latestDayPings($dayDate)
	{
		if (!$table_name = $this->_get_table_name($dayDate)) return $this->error(); 
		if (!Schema::connection('ogn')->hasTable($table_name)) return $this->not_found("Day Not Found");

		// check if we have the vspeed column
		$select_columns='';
		if(Schema::connection('ogn')->hasColumn($table_name, 'vspeed')) {
			$select_columns = ', vspeed';
		}

		$pings = DB::connection('ogn')->select("SELECT d1.id, d1.hex, X(d1.loc) AS lat, Y(d1.loc) AS lng, d1.alt, d1.speed, d1.course".$select_columns." FROM ".$table_name." d1 JOIN (SELECT hex, max(thetime) thetime FROM `".$table_name."`  WHERE hex<>'' GROUP BY hex) d2 ON d1.hex=d2.hex AND d1.thetime=d2.thetime");

		if ($pings)
		{
			// add ground levels for each point
			foreach ($pings AS $key=>$ping)
			{
				$pings[$key]->gl = $this->_get_ground_level($ping->lat, $ping->lng);
				if (!isset($ping->vspeed)) $pings[$key]->vspeed=null;
			}
			return $this->success($pings);
		}
		return $this->error(); 
	}


	public function DayPings($dayDate, $pointsPerHex=5)
	{
		if (!$table_name = $this->_get_table_name($dayDate)) return $this->error(); 
		if (!Schema::connection('ogn')->hasTable($table_name)) return $this->not_found("Day Not Found");
		$pointsPerHex = (int)$pointsPerHex; // ensure $pointsPerHex is an integer

		// first get points with a hex code
		$hexes = DB::connection('ogn')->select('SELECT count(hex) AS hex_count, hex FROM `'.$table_name."`  WHERE hex<>'' GROUP BY hex");
		// get all points without a hex code (i.e. SPOT or cell only)
		$regos = DB::connection('ogn')->select('SELECT count(rego) AS rego_count, rego FROM `'.$table_name."`  WHERE rego<>'' AND hex='' GROUP BY rego");
		$hexArray = Array();
		$regoArray = Array();
		$queries = Array();

		// check if we have the vspeed column
		$select_columns='';
		if(Schema::connection('ogn')->hasColumn($table_name, 'vspeed')) {
			$select_columns = ', vspeed';
		}

		if ($hexes)
		{
			// then craft a query to get latest x points 
			foreach ($hexes AS $hex)
			{
				$hexArray[]=$hex->hex;
				$queries[] = "(SELECT id, thetime, X(loc) AS lat, Y(loc) AS lng, hex, alt, speed, course, rego, type".$select_columns." FROM `".$table_name."` WHERE hex=? ORDER BY thetime DESC LIMIT " . $pointsPerHex . ")";
			}
		}
		if ($regos)
		{
			// then craft a query to get latest x points 
			foreach ($regos AS $rego)
			{
				$hexArray[]=$rego->rego;
				$queries[] = "(SELECT id, thetime, X(loc) AS lat, Y(loc) AS lng, REPLACE(rego, 'ZK-', '') AS hex, alt, speed, course, rego, type".$select_columns." FROM `".$table_name."` WHERE rego=? ORDER BY thetime DESC LIMIT " . $pointsPerHex . ")";
			}
		}

		if ($queries)
		{
			$pings = DB::connection('ogn')->select(implode($queries, ' UNION ALL '), $hexArray);

			if ($pings)
			{
				// add ground levels for each point
				foreach ($pings AS $key=>$ping)
				{
					$pings[$key]->gl = $this->_get_ground_level($ping->lat, $ping->lng);
					if (!isset($ping->vspeed)) $pings[$key]->vspeed=null;
				}

				return $this->success($pings);
			}
		}

		return $this->error(); 
	}


	public function dayHexPings(Request $request, $dayDate, $hex)
	{
		if (!$table_name = $this->_get_table_name($dayDate)) return $this->error(); 
		if (!Schema::connection('ogn')->hasTable($table_name)) return $this->not_found("Day Not Found");

		$rego = $hex;

		$order='DESC';
		if (strtoupper($request->query('order'))=='ASC') $order='';

		// check if we have the vspeed column
		$select_columns='';
		if(Schema::connection('ogn')->hasColumn($table_name, 'vspeed')) {
			$select_columns = ', vspeed';
		}

		$pings = DB::connection('ogn')->select("SELECT id, thetime, X(loc) AS lat, Y(loc) AS lng, hex, alt, speed, course, rego, type".$select_columns." FROM `".$table_name."` WHERE hex=? OR rego=? ORDER BY thetime " . $order, [$hex, $rego]);

		if ($pings)
		{

			// add ground levels for each point
			foreach ($pings AS $key=>$ping)
			{
				$pings[$key]->gl = $this->_get_ground_level($ping->lat, $ping->lng);
				if (!isset($ping->vspeed)) $pings[$key]->vspeed=null;
			}
			
			return $this->success($pings);
		}
		return $this->error(); 
	}

	protected function _get_table_name($dayDate)
	{
		if ($dayDate==null || $dayDate=='' || $dayDate=='null') return false;
		$date = new \DateTime($dayDate);
		$table_name = 'data' . $date->format('Ymd');

		return $table_name;
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
