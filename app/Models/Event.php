<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Duty
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


    protected $dates = ['deleted_at','start_date','end_date','earlybird'];


    public $fillable = [
        'org_id',
        'name',
        'slug',
        'location',
        'waypoint_id',
        'organiser_user_id',
        'creator_user_id',
        'website',
        'email',
        'instagram',
        'facebook',
        'start',
        'end',
        'earlybird',
        'practice_days',
        'terms',
        'details',
        'cost',
        'cost_earlybird'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'org_id'=>'integer',
        'name'=>'integer',
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
        'cost'=>'decimal',
        'cost_earlybird'=>'decimal'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
