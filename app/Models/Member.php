<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\MemberRetrieved;
use Illuminate\Notifications\Notifiable;
use JustBetter\PaginationWithHavings\PaginationWithHavings;
use Gate;

class Member extends Model
{
	use Notifiable;
	use PaginationWithHavings;

	protected $appends = ['can_edit'];
	protected $with = ['affiliates']; // eager load the affiliates

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
			->using('App\Models\Affiliate');
	}

	public function affiliates()
	{
		return $this->hasMany('App\Models\Affiliate');
	}

	// can the current logged in user edit this model?
	public function getCanEditAttribute()
	{
		// if you are an admin or awards editor
		if (Gate::allows('admin')) return true;
		if (Gate::allows('edit-awards', $this)) return true;

		// if you are editing yourself, then yes!
		if (Gate::allows('edit-self', $this)) return true;

		foreach ($this->affiliates AS $affiliate)
		{
			// check we have permission to create a new member
			if (Gate::allows('club-admin', $affiliate->org_id)) {
				return true;
			}
		} 

		return false;
	}

}
