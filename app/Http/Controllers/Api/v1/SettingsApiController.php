<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;

use App\Http\Requests;
use App\Models\RoleUser;
use App\Models\Setting;
use App\Models\Org;
use Auth;
use Gate;

class SettingsApiController extends ApiController
{
	public function index()
	{
		// get all public settings
		if ($settings = Setting::where('protected', false)->whereIsNull('org_id')->get())
		{
			return $this->success($roles);
		}
		return $this->error(); 
	}


	/**
	 * Get non org settings
	 */
	public function get()
	{
		return $this->getOrg(null);
	}


	/**
	 * Get org settings
	 */
	public function getOrg($org_id)
	{
		// get the current user
		$user = Auth::user();

		$query = Setting::where('org_id', $org_id);

		if ($org_id!=null)
		{
			// check the org exists
			if (!$org = Org::where('id', $org_id)->first()) {
				return $this->error("Org does not exist"); 
			}
			// club protection
			if (Gate::denies('club-admin', $org)) {
				$query->where('protected', false);
			}
		}

		// global settings protection
		if ($org_id==null) {
			if (Gate::denies('admin')) {
				$query->where('protected', false);
			}
		}

		// get all settings
		if ($settings = $query->get())
		{
			$data = [];
			foreach ($settings AS $setting)
			{
				$data[$setting->name] = Array('value'=>$setting->value, 'protected'=>$setting->protected);
			}
			return $this->success($data);
		}
		return $this->error(); 
	}




	public function insert(Request $request)
	{
		return $this->insertOrg($request, null);
	}


	public function insertOrg(Request $request, $org_id)
	{
		if ($org_id!=null)
		{
			// get org
			if (!$org = Org::where('id', $org_id)->first()) {
				return $this->error("Org does not exist"); 
			}
			if (Gate::denies('club-admin', $org)) {
				return $this->error("No permission to edit settings"); 
			}
		}

		// global settings protection
		if ($org_id==null) {
			if (Gate::denies('admin')) {
				$query->where('protected', false);
			}
		}

		if (!$request->has('settings')) return $this->error('new settings are required'); 



		foreach ($request->input('settings') AS $key=>$value)
		{
			$setting = Setting::updateOrCreate(
				['name' => $key, 'org_id' => $org_id],
				['name' => $key, 'value'=>$value['value'], 'protected'=>$value['protected'], 'org_id' => $org_id] 
			);
			$setting->save();
		}
		

		return $this->success();
	}

}
