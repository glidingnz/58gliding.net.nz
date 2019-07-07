<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Roster
 * @package App\Models
 * @version July 7, 2019, 9:23 am UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection 
 * @property \Illuminate\Database\Eloquent\Collection 
 * @property integer org_id
 * @property integer day_id
 * @property string day_date
 * @property integer dayrole_id
 * @property integer member_id
 * @property string duty_name
 * @property string helper_name
 * @property string helper_mobile
 */
class Roster extends Model
{
    use SoftDeletes;

    public $table = 'rosters';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];


    public $fillable = [
        'org_id',
        'day_id',
        'day_date',
        'dayrole_id',
        'member_id',
        'duty_name',
        'helper_name',
        'helper_mobile'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'org_id' => 'integer',
        'day_id' => 'integer',
        'day_date' => 'date',
        'dayrole_id' => 'integer',
        'member_id' => 'integer',
        'duty_name' => 'string',
        'helper_name' => 'string',
        'helper_mobile' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
