<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Upload extends Model
{
	use SoftDeletes;

	public $table = 'uploads';
	
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';

	protected $dates = ['created_at','updated_at'];

	public function RatingMember()
	{
		return $this->belongsToMany('App\Models\RatingMember');
	}

	public function uploadable()
	{
		return $this->morphTo();
	}

	public $fillable = [
		'user_id',
		'org_id',
		'filename',
		'slug',
		'icon',
		'type',
		'description'
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id' => 'integer',
		'user_id' => 'integer',
		'org_id' => 'integer',
		'filename' => 'string',
		'slug' => 'string',
		'icon' => 'string',
		'type' => 'string',
		'description' => 'string',
	];

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public static $rules = [
		
	];

	
}
