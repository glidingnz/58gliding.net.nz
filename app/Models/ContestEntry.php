<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContestEntry extends Model
{
    protected $table='entries';
    protected $guarded=['id'];
    protected $hidden=['pivot'];

    public function contest()
    {
        return $this->hasOne('App\Models\Contest','id','contest_id');
    }

    public function contestClass()
    {
        return $this->hasOne('App\Models\ContestClass','id','classes_id');
    }

}
