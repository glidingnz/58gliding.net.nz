<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\ApiController;
use App\Models\Aircraft;
use DB;
use File;

class AircraftApiController extends ApiController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$queryAircraft = Aircraft::query();
		$queryAircraft->orderBy('rego');

		if ($request->input('search'))
		{
			$s = '%' . $request->input('search') .'%';
			$queryAircraft->where(function($queryAircraft) use ($s) {
				$queryAircraft->where('rego','like',$s);
				$queryAircraft->orWhere('manufacturer','like',$s);
				$queryAircraft->orWhere('model','like',$s);
				$queryAircraft->orWhere('owner','like',$s);
			});
		}

		if ($request->input('search-rego'))
		{
			$s = '%' . $request->input('search-rego') .'%';
			$queryAircraft->where(function($queryAircraft) use ($s) {
				$queryAircraft->where('rego','like',$s);
			});
		}


		switch ($request->input('type'))
		{
			case 'glider':
			case 'singles':
			case 'twins':
				$queryAircraft->where(function($queryAircraft) {
					$queryAircraft->where('class','=','Glider');
					$queryAircraft->orWhere('class','=','Power Glider');
					$queryAircraft->orWhere('class','=','Amateur Built Glider');
				});
				break;
			case 'plane':
			case 'aeroplane':
				$queryAircraft->where(function($queryAircraft) {
					$queryAircraft->where('class','=','Aeroplane');
					$queryAircraft->orWhere('class','=','Amateur Built Aeroplane');
				});
				break;
			case 'microlight':
				$queryAircraft->where(function($queryAircraft) {
					$queryAircraft->where('class','=','Microlight Class 1');
					$queryAircraft->orWhere('class','=','Microlight Class 2');
				});
				break;
			case 'gyrocopter':
			case 'gyroplane':
			case 'gyro':
				$queryAircraft->where('class','=','Gyroplane');
				break;
			case 'helicopter':
				$queryAircraft->where(function($queryAircraft) {
					$queryAircraft->where('class','=','Helicopter');
					$queryAircraft->orWhere('class','=','Amateur Built Helicopter');
				});
				break;
			case 'balloon':
				$queryAircraft->where('class','=','Balloon');
				break;
			case 'tow-plane':
			case 'tow':
			case 'tug':
				$queryAircraft->where('towplane','=','1');
				break;
			case 'self-launch':
			case 'self-launcher':
				$queryAircraft->where('self_launcher','=','1');
				break;
			case 'sustainer':
				$queryAircraft->where('sustainer','=','1');
				break;
			case 'engine':
				$queryAircraft->where(function($queryAircraft) {
					$queryAircraft->where('sustainer','=','1');
					$queryAircraft->orWhere('self_launcher','=','1');
				});
				break;
			case 'vintage':
				$queryAircraft->where('vintage','=','1');
				break;
		}

		// additional glider sub filters
		switch ($request->input('type'))
		{
			case 'singles':
				$queryAircraft->where('seats','=','1');
				break;
			case 'twins':
				$queryAircraft->where('seats','=','2');
				break;
		}



		// additional glider sub filters
		if ($request->input('spots'))
		{
			$queryAircraft->where('spot_feed_id','!=','');
		}


		// case 'Aeroplane':
		// case 'Amateur Built Aeroplane':
		// case 'Amateur Built Glider':
		// case 'Amateur Built Helicopter':
		// case 'Balloon':
		// case 'Glider':
		// case 'Gyroplane':
		// case 'Helicopter':
		// case 'Microlight Class 1':
		// case 'Microlight Class 2':
		// case 'Power Glider':

		

		if ($aircraft = $queryAircraft->paginate($request->input('per-page', 50)))
		{
			return $this->success($aircraft, TRUE);
		}
		return $this->error(); 
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		if ($aircraft = Aircraft::find($id))
		{
			return $this->success($aircraft);
		}
		return $this->error();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $rego)
	{
		if ($aircraft = Aircraft::where('rego', $rego)->first())
		{

			// tracking gear
			if ($request->exists('spot_feed_id')) $aircraft->spot_feed_id = $request->get('spot_feed_id');
			if ($request->exists('spot_esn')) $aircraft->spot_esn = $request->get('spot_esn');
			if ($request->exists('spot_active')) $aircraft->spot_active = $request->get('spot_active');
			if ($request->exists('particle_id')) $aircraft->particle_id = $request->get('particle_id');
			if ($request->exists('flarm')) $aircraft->flarm = $request->get('flarm');
			if ($request->exists('mt600')) $aircraft->mt600 = $request->get('mt600');

			// aircraft details
			if ($request->exists('electric')) $aircraft->electric = $request->get('electric');
			if ($request->exists('jet')) $aircraft->jet = $request->get('jet');
			if ($request->exists('vintage')) $aircraft->vintage = $request->get('vintage');
			if ($request->exists('retractable')) $aircraft->retractable = $request->get('retractable');
			if ($request->exists('sustainer')) $aircraft->sustainer = $request->get('sustainer');
			if ($request->exists('self_launcher')) $aircraft->self_launcher = $request->get('self_launcher');
			if ($request->exists('towplane')) $aircraft->towplane = $request->get('towplane');
			if ($request->exists('seats')) $aircraft->seats = $request->get('seats');
			if ($request->exists('location')) $aircraft->location = $request->get('location');
			if ($request->exists('owner')) $aircraft->owner = $request->get('owner');
			if ($request->exists('trailer')) $aircraft->trailer = $request->get('trailer');
			if ($request->exists('transponder')) $aircraft->transponder = $request->get('transponder');
			if ($request->exists('trailer')) $aircraft->trailer = $request->get('trailer');

			// due dates
			if ($request->exists('annual_inspection_due')) $aircraft->annual_inspection_due = $request->get('annual_inspection_due');
			if ($request->exists('annual_airworthiness_due')) $aircraft->annual_airworthiness_due = $request->get('annual_airworthiness_due');
			if ($request->exists('radio_due')) $aircraft->radio_due = $request->get('radio_due');
			if ($request->exists('transponder_due')) $aircraft->transponder_due = $request->get('transponder_due');
			if ($request->exists('altimeter_due')) $aircraft->altimeter_due = $request->get('altimeter_due');

			$aircraft->save();

			return $this->success($aircraft);
		}
		return $this->not_found();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  string  $rego
	 * @return \Illuminate\Http\Response
	 */
	public function rego($rego)
	{
		if ($aircraft = Aircraft::where('rego', $rego)->first())
		{
			return $this->success($aircraft);
		}
		return $this->not_found();
	}

	/**
	 * Given a list of hex codes or regos, get aircraft
	 */
	public function hexes(Request $request)
	{
		$hexes = Array();

		if ($request->input('hexes')) $hexes = $request->input('hexes');

		// check if we need to remove any null
		foreach ($hexes AS $key=>$hex)
		{
			if ($hex=='') unset($hexes[$key]);

			// if we have 3 characters, then it's a rego that needs the ZK- added back in.
			if (strlen($hex)==3) {
				$hexes[$key] = 'ZK-' . $hex;
			}
		}

		if ($aircraft = DB::table('aircraft')
			->whereIn('flarm', $hexes)
			->orWhereIn('rego', $hexes)
			->get())
		{
			return $this->success($aircraft);
		}
		return $this->not_found();
	}

}
