<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
	protected $table = 'gnz_member';
	//protected $connection = 'gnz';
	public $timestamps = false;

	protected $hidden = array('password', 'salt');
	protected $guarded = ['id'];
}
