<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BadgeMember extends Model
{
	protected $table = 'badge_member';
	protected $fillable = ['badge_id'];

	public function badge()
	{
		return $this->belongsTo(Badge::class);
	}
}
