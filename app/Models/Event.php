<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Gate;
use Auth;

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

        if ($this->org!=null) {
            if (Gate::allows('club-admin', $this->org)) {
                return true;
            }
        }

        return false;
    }

    protected $dates = ['deleted_at','start_date','end_date','earlybird'];


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
        'featured',
        'soaringspot_api_secret',
        'soaringspot_api_client_id',
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
        'start_time'=>'time',
        'end_time'=>'time',
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
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
