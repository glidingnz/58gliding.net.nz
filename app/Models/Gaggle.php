<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Classes\SluggableTrait;

class Gaggle extends Model
{
	use SluggableTrait;

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

	// relationships
	public function org() { 
		return $this->belongsTo('App\Models\Org'); 
	}


}
