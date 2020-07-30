<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
	public $table = "Entries2";
	
	public function member() {
		return $this->hasOne('App\Models\Member', 'id', 'member_id');
	}

	public function event() {
		return $this->hasOne('App\Models\Event', 'id', 'event_id');
	}
}
