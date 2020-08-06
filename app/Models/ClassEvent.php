<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassEvent extends Model
{
	protected $table = 'class_event';

	public function event() {
		return $this->hasOne('App\Models\Event', 'id', 'event_id');
	}

	public function contestClass() {
		return $this->hasOne('App\Models\ContestClass', 'id', 'class_id');
	}
}