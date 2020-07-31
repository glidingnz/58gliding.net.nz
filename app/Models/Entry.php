<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
	public $table = "Entries2";
	
	protected $casts = [
		'gnz_member' => 'boolean',
		'winglets' => 'boolean',
		'signed' => 'boolean',
	];

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
