<?php
namespace App\Classes;

use Storage;
use App\Models\Aircraft;

/*
What the CAA file looks like when opened
Array
(
    [0] => Glider
    [1] => GXP
    [2] => Schempp-Hirth
    [3] => Discus b
    [4] => NZ1
    [5] => 524
    [6] => Tim
    [7] => Bromhead
    [8] => Piako Gliding Club
    [9] => PO Box 100
    [10] => 
    [11] => Matamata
    [12] => 3440
    [13] => 
    [14] => 
)
*/

class LoadAircraft
{
	public function load_flarm_from_glidernet()
	{
		if ($file = file_get_contents("http://ddb.glidernet.org/download/"))
		{
			Storage::disk('local')->put('flarm.txt', $file);
			return true;
		}
		return false;
	}

	public function import_flarm_db()
	{

		$file = Storage::disk('local')->get('flarm.txt');

		$rows = explode("\n", $file);

		$counter = 0;
		foreach ($rows AS $row) {
			$row = str_getcsv($row);
			$counter++;

			if (isset($row[3]) && preg_match("/^'ZK.*/", $row[3]))
			{
				// remove slashes
				$row[3] = str_replace("'", '', $row[3]);

				$aircraft = Aircraft::where('rego', $row[3])->first();

				if ($aircraft!==null)
				{
					$aircraft->flarm = str_replace("'", '', $row[1]);
					$aircraft->save();
				}
			}
		}


	}


	public function load_db_from_caa()
	{
		if ($file = file_get_contents("https://www.caa.govt.nz/assets/legacy/aircraft/AircraftRegisterExport.zip"))
		{
			Storage::disk('local')->put('aircraft.zip', $file);

			$path = storage_path() . '/app/';
            
            $zip = new \ZipArchive();
            $zip->open($path.'aircraft.zip');
            $zip->extractTo($path);
            rename($path.'AircraftRegisterExport.tab', $path.'aircraft.tab');
             
			//exec('unzip -o '.$path.'aircraft.zip -d '.$path);
			//exec('mv '.$path.'AircraftRegisterExport.tab '.$path.'aircraft.tab');
			return true;
		}
		return false;
	}


	public function import_caa_db()
	{
		$file = Storage::disk('local')->get('aircraft.tab');

		$rows = explode("\n", $file);
		
		$counter = 0;
		foreach ($rows AS $row) {
			$row = explode("\t", $row);
			$counter++;

			foreach($row AS $key=>$string)
			{
				$row[$key] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $string);
			}

			if (isset($row[1]) && $row[1]!='') {

				$rego = 'ZK-'.$row[1];


				// Update existing aircraft
				$aircraft = Aircraft::where('rego', $rego)->first();

				$names = Array();
				if ($row[6]!='') $names[] = $row[6];
				if ($row[7]!='') $names[] = $row[7];



				if ($aircraft!==NULL)
				{
					$changes = false;

					$new_name = implode($names, ' ');
					if ($new_name!=$aircraft->owner) {
						$aircraft->owner = $new_name;
						$changes=true;
					};

					if ($changes) $aircraft->save();
				}


				// Deal with aircraft that aren't already in the DB
				if ($aircraft==NULL)
				{
					$aircraft = new Aircraft;
					$aircraft->rego = $rego;
					$aircraft->manufacturer = $row[2];
					$aircraft->model = $row[3];
					$aircraft->serial = $row[4];
					$aircraft->mctow = $row[5];
					$aircraft->class = $row[0];
					$aircraft->transponder = '';

					$aircraft->owner = implode($names, ' ');
					
					// check if we should shorten the contest rego to 2 letters, only for gliders
					if ($row[0]=='Power Glider' || $row[0]=='Glider')
					{
						$aircraft->contest_id = substr($row[1], 1);
					}
					else
					{
						$aircraft->contest_id = $row[1];
					}

					// check if self launcher or not (roughly)
					if ($row[0]=='Power Glider')
					{
						switch ($row[3]) {
							case 'DG-800B':
							case 'DG-800C':
							case 'DG-808C':
							case 'DG-400':
							case 'PIK-30':
							case 'ASH 26 E':
							case 'ASH 25 M':
							case 'G109':
							case 'Arcus M':
							case 'DG-500MB':
							case 'Janus CM':
								$aircraft->self_launcher = true;
								break;
							default:
								$aircraft->sustainer = true;
								break;
						}
					}


					// check for common glider features
					if ($row[0]=='Power Glider' || $row[0]=='Glider')
					{
						switch ($row[3])
						{
							case 'ASH 26 E':
							case 'ASH 25 M':
							case 'G109':
							case 'Arcus M':
							case 'DG-500MB':
							case 'Janus CM':
							case 'Twin Astir':
							case 'PW-6U':
							case 'G103 Twin II':
							case 'SZD-50-3 Puchacz':
							case 'Duo Discus':
							case 'Janus':
							case 'ASH 25':
							case 'KR-03A "Puchatek"':
							case 'ASH 31 Mi':
							case 'G103A Twin II Acro':
							case 'E.S.52/II Kookaburra':
							case 'ASK 21':
								$aircraft->seats = 2;
								break;
						}

						// check for common non-retractables
						switch ($row[3]) {
							case 'PW-6U':
							case 'SZD-50-3 Puchacz':
							case 'G102 Club Astir IIIB':
							case 'G103 Twin II':
							case 'G103A Twin II Acro':
							case 'PW-5 "Smyk"':
							case 'Ka 6CR':
							case 'K 7 Rhonadler':
							case 'T.43 Skylark 3F':
							case 'ASK 21':
							case 'Baby Eon':
								$aircraft->retractable = false;
								break;
							default:
								$aircraft->retractable = true;
								break;

							// check for jets/electric. For some reason these aren't classified as having engines in CAAs database.
							switch ($row[3])
							{
								case 'JS1-C 18/21':
								case 'JS1-B "Revelation"':
									$aircraft->sustainer = true;
									$aircraft->jet = true;
									break;
								case 'E1 Antares':
									$aircraft->electric = true;
									$aircraft->self_launcher = true;
									break;
							}

						}


						// check for common vintage gliders
						switch ($row[3]) {
							case 'Ka 6CR':
							case 'Ka 6E':
							case 'Standard Libelle':
							case 'H 301 B Libelle':
							case 'H 301 Libelle':
							case 'Standard Libelle 201B':
							case 'Club Libelle 205':
							case 'K 7 Rhonadler':
							case 'T.43 Skylark 3F':
							case 'Baby Eon':
							case 'T.51 Dart':
							case 'T.51 Dart 17R':
							case 'BG 12-16':
							case 'E.S.52/II Kookaburra':
							case 'T.41B Skylark 2':
							case 'T.41B Skylark 2B':
							case 'T.43 Skylark 3F':
							case 'T.50 Skylark 4':
							case 'T.65A Vega':
							case 'K 8B':
								$aircraft->vintage = true;
								break;
							default:
								$aircraft->vintage = false;
								break;
						}
					}


					// check for existing tow planes
					switch ($row[1]) {
						case 'BZA':
						case 'RDW':
						case 'TGC':
						case 'JTA':
						case 'LJW':
						case 'PZL':
						case 'BKJ':
						case 'PNE':
						case 'CEB':
						case 'DSM':
						case 'TOW':
						case 'BFV':
						case 'TPO':
						case 'OMA':
						case 'CNG':
						case 'BNM':
						case 'DNS':
						case 'ERW':
							$aircraft->towplane = true;
							break;
					}

					$aircraft->save();
				}
			}

			/*
			$row = explode("\t", $row);
			//$row = safe_for_db($row);
		
			if (isset($row[1]) && $row[1]!='') {
				$row[1] = 'ZK-'.$row[1];
				$values[]="('{$row[1]}','{$row[0]}','{$row[4]}',{$row[5]},'{$row[2]}','{$row[3]}')";
			}
			*/
		}
	}
}