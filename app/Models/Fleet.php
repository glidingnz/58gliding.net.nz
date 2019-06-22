<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
	protected $table = 'fleet';

	public function aircraft() { 
		return $this->belongsTo('App\Models\Aircraft'); 
	} 

	public function org() { 
		return $this->belongsTo('App\Models\Org'); 
	}
}
