<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Member;
use Gate;
use Auth;

class Entry extends Model
{
	public $table = "Entries2";
	
	protected $casts = [
		'gnz_member' => 'boolean',
		'winglets' => 'boolean',
		'signed' => 'boolean',
	];

	protected $hidden = [
		'emergency_contact',
		'emergency_mobile',
		'emergency_phone',
		'emergency_email',
		'emergency_relationship',
		'crew_name',
		'crew_mobile',
		'car_plate',
		'car_details',
		'crew_name',
		'editcode',
		'mobile',
		'email'
	];


	// check if the current logged in user can edit this entry
	public function canEdit($editcode=null)
	{
		if ($user = Auth::user())
		{
			// The user that created the entry
			if ($this->user_id == $user->id)
			{
				return true;
			}
		}

		// If we are currently logged in as a GNZ member
		if (isset($user)) 
		{
			// Are we logged in as the GNZ member specified in this entry?
			if ($this->member_id==$user->gnz_id) {
				return true;
			}
		}

		// Site Admin/Root
		if (Gate::allows('admin')) return true;

		// Contest admin
		if (Gate::allows('contest-admin')) return true;
		// Someone with the edit code
		if ($editcode==$this->editcode) return true;

		return false;
	}




	public function showDetails()
	{
		$this->makeVisible(
			['emergency_contact',
			'emergency_mobile',
			'emergency_phone',
			'emergency_email',
			'emergency_relationship',
			'crew_name',
			'crew_mobile',
			'car_plate',
			'car_details',
			'crew_name',
			'editcode',
			'mobile',
			'email']
		);
	}

	public function member() {
		return $this->hasOne('App\Models\Member', 'id', 'member_id');
	}

	public function event() {
		return $this->hasOne('App\Models\Event', 'id', 'event_id');
	}

	public function aircraft() {
		return $this->hasOne('App\Models\Aircraft', 'id', 'aircraft_id');
	}

	public function user() {
		return $this->hasOne('App\Models\User', 'id', 'user_id');
	}
}
