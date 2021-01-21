<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
	protected $table="affiliates";

	protected $dates = ['join_date','end_date'];

	public function org()
	{
		return $this->belongsTo('App\Models\Org');
	}


	protected $casts = [
		'join_date'=>'date',
		'end_date'=>'date'
	];
}