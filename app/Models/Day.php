<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Day
 * @package App\Models
 * @version June 27, 2019, 11:54 am UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection 
 * @property \Illuminate\Database\Eloquent\Collection 
 * @property integer org_id
 * @property string day_date
 * @property boolean active
 * @property string description
 * @property boolean trialflights
 * @property boolean competition
 * @property boolean training
 * @property boolean winching
 * @property boolean towing
 * @property string status
 * @property string cancelled_reason
 */
class Day extends Model
{
    use SoftDeletes;

    public $table = 'days';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'org_id',
        'day_date',
        'active',
        'description',
        'trialflights',
        'competition',
        'training',
        'winching',
        'towing',
        'status',
        'cancelled',
        'cancelled_reason'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'org_id' => 'integer',
        'day_date' => 'date',
        'active' => 'boolean',
        'description' => 'string',
        'trialflights' => 'boolean',
        'competition' => 'boolean',
        'training' => 'boolean',
        'winching' => 'boolean',
        'towing' => 'boolean',
        'status' => 'string',
        'cancelled' => 'boolean',
        'cancelled_reason' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'org_id' => 'required',
        'day_date' => 'required',
        // 'active' => 'required',
        // 'trialflights' => 'required',
        // 'competition' => 'required',
        // 'training' => 'required',
        // 'winching' => 'required',
        // 'towing' => 'required',
        // 'status' => 'required',
        // 'cancelled_reason' => 'required'
    ];

    
}
