<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use File;
use Storage;

class Org extends Model
{
	protected $fillable = ['name', 'website', 'slug', 'gnz_code','type'];

	public function aircraft()
	{
		return $this->belongsToMany('App\Models\Aircraft', 'fleet');
	}

	public function getFolderAttribute()
	{
		$path = '/public/' . $this->slug . '/';
		$this->create_folder($path);
		return $path;
	}

	public function getFilesPathAttribute()
	{
		$path = '/storage/' . $this->slug . '/';
		$this->create_folder($path);
		return $path;
	}

	public function create_folder($path)
	{
		if(!File::exists($path)) {
			Storage::makeDirectory($path, 0777, true, true);
		}
	}

	/*
	public function fleet()
	{
		return $this->hasMany('Fleet');
	}
	*/
}
