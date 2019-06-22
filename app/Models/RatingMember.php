<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingMember extends Model
{
	protected $table = 'rating_member';

	public function member() {
		return $this->hasOne('App\Models\Member', 'id', 'member_id');
	}
	public function authorisingMember() {
		return $this->hasOne('App\Models\Member', 'id', 'authorising_member_id');
	}
}
