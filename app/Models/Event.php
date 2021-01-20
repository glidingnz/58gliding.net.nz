<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Gate;
use Auth;
use Carbon\Carbon;
use App\Classes\SluggableTrait;

/**
 * Class Event
 * @package App\Models
 * @version July 7, 2019, 4:09 am UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection 
 * @property \Illuminate\Database\Eloquent\Collection 
 * @property integer org_id
 * @property string name
 */
class Event extends Model
{
	use SoftDeletes;
	use SluggableTrait;

	public $table = 'events';
	
	const CREATED_AT = 'created_at';
	const UPDATED_AT = 'updated_at';

	public function org()
	{
		return $this->belongsTo('App\Models\Org');
	}


	/**
	 * Can the current user edit this event?
	 * @return boolean true or false
	 */
	public function getCanEditAttribute()
	{
		if (Auth::user())
		{
			if ($this->creator_user_id == Auth::user()->id)
			{
				return true;
			}
		}

		// if no org, it must be a GNZ event, so check for GNZ admin
		if (Gate::allows('admin')) return true;

		// all contest admins can edit all events
		if (Gate::allows('contest-admin')) return true;

		// club members can add/edit events
		if ($this->org!=null) {
			if (Gate::allows('club-member', $this->org)) {
				return true;
			}
		}

		return false;
	}

	// public function getSoaringspot_api_secretAttribute()
	// {
	// 	if ($this->getCanEditAttribute()) {
			
	// 	}
	// }

	protected $dates = ['deleted_at','start_date','end_date','earlybird'];

	protected $hidden = array('soaringspot_api_secret');

	public $fillable = [
		'org_id',
		'name',
		'type',
		'slug',
		'location',
		'waypoint_id',
		'organiser_user_id',
		'creator_user_id',
		'website',
		'email',
		'instagram',
		'facebook',
		'start_date',
		'end_date',
		'start_time',
		'end_time',
		'earlybird',
		'practice_days',
		'terms',
		'details',
		'cost',
		'cost_earlybird',
		'organiser_member_id',
		'organiser_name',
		'organiser_phone',
		'featured',
		'soaringspot_api_secret',
		'soaringspot_api_client_id',
		'catering_lunches',
		'catering_dinners',
		'catering_breakfasts',
		'catering_final_dinner',
		'entries_active'
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'org_id'=>'integer',
		'name'=>'string',
		'type'=>'string',
		'slug'=>'string',
		'location'=>'string',
		'waypoint_id'=>'integer',
		'organiser_user_id'=>'integer',
		'creator_user_id'=>'integer',
		'website'=>'string',
		'email'=>'string',
		'instagram'=>'string',
		'facebook'=>'string',
		'start_date'=>'date',
		'end_date'=>'date',
		'start_time'=>'string',
		'end_time'=>'string',
		'earlybird'=>'date',
		'practice_days'=>'integer',
		'terms'=>'string',
		'details'=>'string',
		'cost'=>'decimal:2',
		'cost_earlybird'=>'decimal:2',
		'organiser_member_id'=>'integer',
		'featured'=>'boolean',
		'soaringspot_api_secret'=>'string',
		'soaringspot_api_client_id'=>'string',
		'organiser_name'=>'string',
		'organiser_phone'=>'string',
		'catering_lunches'=>'boolean',
		'catering_dinners'=>'boolean',
		'catering_breakfasts'=>'boolean',
		'catering_final_dinner'=>'boolean',
		'entries_active'=>'boolean'
	];

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public static $rules = [
		
	];

	public function setStartTimeAttribute($value)
	{
		try {
			$time = Carbon::createFromFormat('H:i', $value);
			$this->attributes['start_time'] = $time->format('H:i');
		} catch (\Exception $err) {}

		try {
			$time = Carbon::createFromFormat('H:i:s', $value);
			$this->attributes['start_time'] = $time->format('H:i:s');
		} catch (\Exception $err) {}

		try {
			$time = Carbon::createFromFormat('ha', $value);
			$this->attributes['start_time'] = $time->format('H:i:s');
		} catch (\Exception $err) {}

		try {
			$time = Carbon::createFromFormat('h:ia', $value);
			$this->attributes['start_time'] = $time->format('H:i:s');
		} catch (\Exception $err) {}

	}

	public function setEndTimeAttribute($value)
	{
		try {
			$time = Carbon::createFromFormat('H:i', $value);
			$this->attributes['end_time'] = $time->format('H:i:s');
		} catch (\Exception $err) {}

		try {
			$time = Carbon::createFromFormat('H:i:s', $value);
			$this->attributes['end_time'] = $time->format('H:i:s');
		} catch (\Exception $err) {}

		try {
			$time = Carbon::createFromFormat('ha', $value);
			$this->attributes['end_time'] = $time->format('H:i:s');
		} catch (\Exception $err) {}

		try {
			$time = Carbon::createFromFormat('h:ia', $value);
			$this->attributes['end_time'] = $time->format('H:i:s');
		} catch (\Exception $err) {}
	}


	public function getStartTimeAttribute($value)
	{
		try {
			$time = Carbon::createFromFormat('H:i:s', $value); 
			return $time->format('g:ia');
		} catch (\Exception $err) {}
		try {
			$time = Carbon::createFromFormat('H:i', $value); 
			return $time->format('g:ia');
		} catch (\Exception $err) {}
	}

	public function getEndTimeAttribute($value)
	{
		try {
			$time = Carbon::createFromFormat('H:i:s', $value); 
			return $time->format('g:ia');
		} catch (\Exception $err) {}
		try {
			$time = Carbon::createFromFormat('H:i', $value); 
			return $time->format('g:ia');
		} catch (\Exception $err) {}
	}
}
