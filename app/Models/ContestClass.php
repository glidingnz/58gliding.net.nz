<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContestClass extends Model
{
    protected $table = 'classes';
    protected $fillable = ['id','name','description','attribute_1','attribute_2','attribute_3','attribute_4','attribute_5'];
}