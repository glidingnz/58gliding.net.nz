<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\MemberRetrieved;
use Illuminate\Notifications\Notifiable;

class Member extends Model
{
	use Notifiable;

	protected $table = 'gnz_member';
	public $timestamps = false;

	protected $hidden = array('password', 'salt');
	protected $guarded = ['id'];


	protected static function boot()
	{
		parent::boot();
	}

	public function orgs()
	{
		return $this->belongsToMany('App\Models\Org')
			->withTimestamps()
			->using('App\Models\MemberOrg');
	}

}
