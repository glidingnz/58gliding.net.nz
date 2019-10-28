<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RatingMember extends Pivot
{
	public $incrementing = true;
	protected $table = 'rating_member';

	public function member() {
		return $this->hasOne('App\Models\Member', 'id', 'member_id');
	}
	public function rating() {
		return $this->hasOne('App\Models\Rating', 'id', 'rating_id');
	}
	public function uploads() {
		return $this->morphMany('App\Models\Upload', 'uploadable');
	}
	public function authorisingMember() {
		return $this->hasOne('App\Models\Member', 'id', 'authorising_member_id');
	}
}
