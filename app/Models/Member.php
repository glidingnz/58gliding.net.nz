<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\MemberRetrieved;
use Illuminate\Notifications\Notifiable;
use JustBetter\PaginationWithHavings\PaginationWithHavings;

class Member extends Model
{
	use Notifiable;
	use PaginationWithHavings;

	protected $table = 'gnz_member';
	public $timestamps = false;

	protected $hidden = array('password', 'salt');
	protected $guarded = ['id'];


	protected static function boot()
	{
		parent::boot();
	}

}
