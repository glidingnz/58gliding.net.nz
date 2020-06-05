<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
	protected $table="affiliates";

	public function org()
	{
		return $this->belongsTo('App\Models\Org');
	}
}