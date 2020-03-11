<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Classes\SluggableTrait;

class Membertype extends Model
{
	use SluggableTrait;
	
	public $fillable = [
		'name',
		'slug',
		'org_id'
	];
}
