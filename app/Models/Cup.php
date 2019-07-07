<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cup extends Model
{

    protected $fillable = ['id','name','description'];

    public function waypoints()
    {
        return $this->belongsToMany('App\Models\Waypoint');
    }
}
