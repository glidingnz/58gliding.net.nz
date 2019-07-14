<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cup extends Model
{

    protected $fillable = ['id','name','description'];
    protected $hidden = ['pivot'];

    public function waypoints()
    {
        return $this->belongsToMany('App\Models\Waypoint');
    }
}
