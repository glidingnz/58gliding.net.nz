<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aircraft extends Model
{
	protected $table = 'aircraft';

	public function orgs()
	{
		return $this->belongsToMany('App\Models\Org', 'fleet');
	}

	/**
	 * The roles that belong to the user.
	 */
	public function gaggles()
	{
		return $this->belongsToMany('App\Models\Gaggle');
	}
}
