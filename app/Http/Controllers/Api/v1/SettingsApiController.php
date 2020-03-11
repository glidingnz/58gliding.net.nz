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
		// get the current user
		$user = Auth::user();

		// get all settings
		if ($settings = Setting::where('protected', false)->whereIsNull('org_id')->get())
		{
			return $this->success($roles);
		}
		return $this->error(); 
	}


	public function org($org_id)
	{
		// get the current user
		$user = Auth::user();

		if (!$org = Org::where('id', $org_id)->first()) {
			return $this->error("Org does not exist"); 
		}

		$query = Setting::where('org_id', $org_id);
		if (Gate::denies('club-admin', $org)) {
			$query->where('protected', false);
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


	public function insert(Request $request, $org_id)
	{

		// get org
		if (!$org = Org::where('id', $org_id)->first()) {
			return $this->error("Org does not exist"); 
		}
		if (Gate::denies('club-admin', $org)) {
			return $this->error("No permission to edit settings"); 
		}

		if (!$request->has('settings')) return $this->error('new settings are required'); 

		foreach ($request->input('settings') AS $key=>$value)
		{
			$setting = Setting::updateOrCreate(
				['name' => $key],
				['name' => $key, 'value'=>$value['value'], 'protected'=>$value['protected'], 'org_id' => $org->id] 
			);
			$setting->save();
		}
		

		return $this->success();
	}

}
