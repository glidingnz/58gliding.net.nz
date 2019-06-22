<?php
namespace App\Classes;
use App\Models\Waypoint;
use Storage;

class WaypointsLibrary
{
	/**
	 * Given a filepath of a cup file, return an array of Waypoints
	 */
	public function process_cup_file($filepath)
	{
		/*
		[0] => Name
		[1] => Code
		[2] => Country
		[3] => Latitude
		[4] => Longitude
		[5] => Elevation
		[6] => Style
		[7] => Direction
		[8] => Length
		[9] => Frequency
		[10] => Description
		*/

		$data =  Storage::get($filepath);

		$rows = preg_split("/\r\n|\n|\r/", $data);
		$batch = $this->get_next_batch();
		$waypoints = Array(); // store the results in here

		foreach ($rows AS $row)
		{
			$waypoint = new Waypoint;

			$row = str_getcsv($row);

			if (preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($row[0]))=='title') continue;
			if (preg_replace("/[^A-Za-z0-9 ]/", '', strtolower($row[0]))=='name') continue;
			if (count($row)<10) continue;

			$waypoint->name = trim($row[0],'"');
			$waypoint->description = trim($row[10],'"');
			$waypoint->style = trim($row[6],'"');

			if (substr($row[5], -1)=='m') $waypoint->elevation = $this->meters_to_feet(substr($row[5], 0, -1));
			if (substr($row[5], -2)=='ft') $waypoint->elevation = substr($row[5], 0, -2);

			// set code to be the code field
			$waypoint->code=trim($row[1],'"');

			$waypoint->direction=trim($row[7],'"');

			if (substr($row[8], -1)=='m') $waypoint->length = substr($row[8], 0, -1);
			if (substr($row[8], -2)=='ft') $waypoint->length = $this->feet_to_meters(substr($row[8], 0, -2));



			$waypoint->frequency=trim($row[9],'"');

			// check if the name has a number at the front from existing files
			$split_name = preg_split("/([0-9]+)[ ]+/", $waypoint->name, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
			if (count($split_name)==2)
			{
				$waypoint->name = $split_name[1];
				$waypoint->number = $split_name[0];
			}

			// check for 3 letter codes from Murray
			$split_name = preg_split("/([0-9A-Z]{3})[ ]+/", $waypoint->name, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
			if (count($split_name)==2)
			{
				$waypoint->name = $split_name[1];
			}

			$waypoint->lat = $this->convertDMSToDecimal($row[3]);
			$waypoint->long = $this->convertDMSToDecimal($row[4]);

			$waypoint->batch = $batch;

			$waypoints[] = $waypoint;
		}
		return $waypoints;
	}


	public function get_next_batch()
	{
		return 0;
	}

	public function feet_to_meters($feet) 
	{
		return round(floatval($feet) / 3.2808399);
	}


	public function meters_to_feet($meters) 
	{
		return round(floatval($meters) * 3.2808399);
	}

	/*
	 * Convert DMS (degrees / minutes / seconds) to decimal degrees
	 *
	 * Todd Trann
	 * May 22, 2015
	 */
	public function convertDMSToDecimal($latlng)
	{
		$valid = false;
		$decimal_degrees = 0;
		$degrees = 0; $minutes = 0; $seconds = 0; $direction = 1;
		// Determine if there are extra periods in the input string
		$num_periods = substr_count($latlng, '.');
		if ($num_periods > 1) {
			$temp = preg_replace('/\./', ' ', $latlng, $num_periods - 1); // replace all but last period with delimiter
			$temp = trim(preg_replace('/[a-zA-Z]/','',$temp)); // when counting chunks we only want numbers
			$chunk_count = count(explode(" ",$temp));
			if ($chunk_count > 2) {
				$latlng = preg_replace('/\./', ' ', $latlng, $num_periods - 1); // remove last period
			} else {
				$latlng = str_replace("."," ",$latlng); // remove all periods, not enough chunks left by keeping last one
			}
		}
		
		// Remove unneeded characters
		$latlng = trim($latlng);
		$latlng = str_replace("º"," ",$latlng);
		$latlng = str_replace("°"," ",$latlng);
		$latlng = str_replace("'"," ",$latlng);
		$latlng = str_replace("\""," ",$latlng);
		$latlng = str_replace("  "," ",$latlng);
		$latlng = substr($latlng,0,1) . str_replace('-', ' ', substr($latlng,1)); // remove all but first dash
		if ($latlng != "") {
			// DMS with the direction at the start of the string
			if (preg_match("/^([nsewNSEW]?)\s*(\d{1,3})\s+(\d{1,3})\s+(\d+\.?\d*)$/",$latlng,$matches)) {
				$valid = true;
				$degrees = intval($matches[2]);
				$minutes = intval($matches[3]);
				$seconds = floatval($matches[4]);
				if (strtoupper($matches[1]) == "S" || strtoupper($matches[1]) == "W")
					$direction = -1;
			}
			// DMS with the direction at the end of the string
			elseif (preg_match("/^(-?\d{1,3})\s+(\d{1,3})\s+(\d+(?:\.\d+)?)\s*([nsewNSEW]?)$/",$latlng,$matches)) {
				$valid = true;
				$degrees = intval($matches[1]);
				$minutes = intval($matches[2]);
				$seconds = floatval($matches[3]);
				if (strtoupper($matches[4]) == "S" || strtoupper($matches[4]) == "W" || $degrees < 0) {
					$direction = -1;
					$degrees = abs($degrees);
				}
			}
			if ($valid) {
				// A match was found, do the calculation
				$decimal_degrees = ($degrees + ($minutes / 60) + ($seconds / 3600)) * $direction;
			} else {
				// Decimal degrees with a direction at the start of the string
				if (preg_match("/^([nsewNSEW]?)\s*(\d+(?:\.\d+)?)$/",$latlng,$matches)) {
					$valid = true;
					if (strtoupper($matches[1]) == "S" || strtoupper($matches[1]) == "W")
						$direction = -1;
					$decimal_degrees = $matches[2] * $direction;
				}
				// Decimal degrees with a direction at the end of the string
				elseif (preg_match("/^(-?\d+(?:\.\d+)?)\s*([nsewNSEW]?)$/",$latlng,$matches)) {
					$valid = true;
					if (strtoupper($matches[2]) == "S" || strtoupper($matches[2]) == "W" || $degrees < 0) {
						$direction = -1;
						$degrees = abs($degrees);
					}
					$decimal_degrees = $matches[1] * $direction;
				}
			}
		}
		if ($valid) {
			return $decimal_degrees;
		} else {
			return false;
		}
	}
}