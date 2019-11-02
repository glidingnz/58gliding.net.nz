<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Classes\SluggableTrait;

class Fleet extends Model
{
	use SluggableTrait;
	protected $table = 'fleets';

	public $fillable = [
		'name',
		'slug',
		'org_id',
		'user_id'
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id' => 'integer',
		'name' => 'string',
		'slug' => 'string',
		'org_id' => 'integer',
		'user_id' => 'integer'
	];

	public function aircraft() { 
		return $this->belongsToMany('App\Models\Aircraft'); 
	} 

	public function org() { 
		return $this->belongsTo('App\Models\Org'); 
	}
}