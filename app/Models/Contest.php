<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    protected $fillable = ['id','name','description','practice','start','end','website','contact','email','location','attribute_1','attribute_2','attribute_3','attribute_4','attribute_5'];
    protected $hidden = ['pivot'];

    public function contestEntry()
    {
        return $this->hasMany('App\Models\ContestEntries','id','contest_id');
    }

    public function contestClass()
    {
        return $this->belongsToMany('App\Models\ContestClass','classes_contests','contest_id','class_id');
    }
}
