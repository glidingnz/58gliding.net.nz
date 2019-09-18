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

    public function member()
    {
        return $this->belongsTo('App\Models\Member');
    } 

    public $fillable = [
        'org_id',
        'day_id',
        'day_date',
        'member_id',
        'duty_id',
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
        'member_id' => 'integer',
        'duty_id' => 'integer',
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
