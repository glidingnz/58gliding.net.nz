<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waypoint extends Model
{
	public function cups()
	{
		return $this->belongsToMany('App\Models\Cups');
	}
}
