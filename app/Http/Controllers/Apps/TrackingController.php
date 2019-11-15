<?php
namespace App\Http\Controllers\Apps;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Classes\LoadAircraft;
use App\Models\Aircraft;

class TrackingController extends Controller
{
	public function index()
	{
		return view('tracking/map');
	}
	public function mapbox()
	{
		return view('tracking/mapbox');
	}
	public function spots()
	{
		return view('tracking/spots');
	}
	public function day($year, $month, $day)
	{
		$data['month']=$month;
		$data['day']=$day;
		$data['year']=$year;
		return view('tracking/map', $data);
	}

	public function track($year, $month, $day, $rego)
	{
		$data['month']=$month;
		$data['day']=$day;
		$data['year']=$year;
		$data['rego']=$rego;

		$queryAircraft = Aircraft::query();
		$queryAircraft->where('rego','=',$rego)->orWhere('flarm','=', $rego);
		$data['aircraft'] = $queryAircraft->first();

		if ($data['aircraft']) $data['hex'] = $data['aircraft']['flarm'];
		else $data['hex'] = $rego;

		return view('tracking/track', $data);
	}

	public function day2($year, $month, $day)
	{
		$data['month']=$month;
		$data['day']=$day;
		$data['year']=$year;
		return view('tracking/map2', $data);
	}
}
