<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Org extends Model
{
	protected $fillable = ['name', 'website', 'slug', 'gnz_code','type'];

	public function aircraft()
	{
		return $this->belongsToMany('App\Models\Aircraft', 'fleet');
	}

	/*
	public function fleet()
	{
		return $this->hasMany('Fleet');
	}
	*/
}
