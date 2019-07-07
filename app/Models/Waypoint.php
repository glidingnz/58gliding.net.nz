<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Waypoint extends Model
{
    use PresentableTrait;

    protected $presenter = 'App\Models\Presenters\WaypointPresenter';

    protected $fillable = ['id','name','code','country','lat','long','elevation','style','direction','length','frequency','description'];

    public function cups()
    {
        return $this->belongsToMany('App\Models\Cup');
    }
}
