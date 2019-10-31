<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Api\ApiController;
use App\Models\Badge;

use Gate;
use Auth;

class BadgesApiController extends ApiController
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{

		$query = Badge::query();

		$type = strtolower($request->input('type'));

		switch ($type)
		{
			case 'general':
			case 'olc':
			case 'outlanding':
			case 'fai badges':
			case 'contest':
				$query->where('type','=',$type);
				break;
		}

		if ($request->input('exclude')=='fai')
		{
			$query->where('type','!=','fai badges');
			//$query->where('type','!=','fai awards');
		}

		$query->where('type', '!=', 'FAI Awards');

		$query->orderBy('type');

		if ($results = $query->paginate($request->input('per-page', 100)))
		{
			return $this->success($results, TRUE);
		}
		return $this->error();
	}

}