<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth;
use DB;
use App;

class ApiController extends Controller
{
	protected $data;
	public $timezone;

	public function __construct()
	{
		if (App::environment('local') || env('APP_DEBUG')) {
			// The environment is local
			DB::enableQueryLog();
			DB::connection('ogn')->enableQueryLog();
		}
		$this->data['success']=false;
		$this->data['http_code']=500;

		$this->middleware(function ($request, $next) {
			$this->timezone = new \DateTimeZone($request->input('timezone', 'Pacific/Auckland'));
			return $next($request);
		});

		parent::__construct();
	}

	public function success($data=[], $paginated=false)
	{
		if ($paginated)
		{
			$item = $data->toArray();
			$data = $item['data'];
			unset($item['data']);
			$this->data = array_merge($this->data, $item);
		}
		$this->data['data'] = $data;
		$this->data['success']=true;
		$this->data['http_code']=200;
		$this->_get_db_queries();

		return $this->data;
	}

	public function not_found($message="Not Found")
	{
		$this->data['error']=$message;
		$this->data['success']=false;
		$this->data['http_code']=404;
		$this->_get_db_queries();
		return response()->json($this->data)->setStatusCode(404);
	}

	public function bad_request($message="Bad Request")
	{
		$this->data['error']=$message;
		$this->data['success']=false;
		$this->data['http_code']=400;
		$this->_get_db_queries();
		return $this->data;
	}

	public function error($message="An Unknown Error Occured")
	{
		if (is_array($message))
		{
			$this->data['error']=implode($message, ', ');
		}
		else
		{
			$this->data['error']=$message;
		}
		
		$this->data['success']=false;
		$this->data['http_code']=500;
		$this->_get_db_queries();
		return response()->json($this->data)->setStatusCode(500);
	}

	public function denied($message="Permission Denied")
	{
		$this->data['error']=$message;
		$this->data['success']=false;
		$this->data['http_code']=403;
		$this->_get_db_queries();
		return response()->json($this->data)->setStatusCode(403);
	}

	protected function _get_db_queries()
	{

		if (!(App::environment('local') || env('APP_DEBUG'))) {
			return null;
		}

		$this->data['queries_db']=DB::getQueryLog();
		$this->data['queries_db_total']=count(DB::getQueryLog());
		$this->data['queries_ogn']=DB::connection('ogn')->getQueryLog();
		$this->data['queries_ogn_total']=count(DB::connection('ogn')->getQueryLog());


		foreach($this->data['queries_db'] as $key=>$query) {
			$query_string = str_replace(array('?'), array('\'%s\''), $query['query']);
			$query_string = str_replace(array('%'), array('%%'), $query['query']); // fix to ensure any existing % are ignored by vsprintf next
			$query_string = vsprintf($query_string, $query['bindings']);
			$this->data['queries_db'][$key]['raw'] = $query_string;
		}
		foreach($this->data['queries_ogn'] as $key=>$query) {
			$query_string = str_replace(array('?'), array('\'%s\''), $query['query']);
			$query_string = str_replace(array('%'), array('%%'), $query['query']); // fix to ensure any existing % are ignored by vsprintf next
			$query_string = vsprintf($query_string, $query['bindings']);
			$this->data['queries_ogn'][$key]['raw'] = $query_string;
		}

		$total_time = 0;
		foreach ($this->data['queries_db'] AS $query) {
			$total_time += $query['time'];
		}
		foreach ($this->data['queries_ogn'] AS $query) {
			$total_time += $query['time'];
		}
		$this->data['queries_time_seconds']=round($total_time/1000, 4);
	}

	public function fetch_user()
	{
		$user = Auth::user();
		if ($user==null)
		{
			// try OAuth user
			if ($userID = Authorizer::getResourceOwnerId())
			{
				// fetch the user
				$user = User::find($userID);
			}
		}
		return $user;
	}


}
