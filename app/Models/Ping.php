<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ping extends Model
{
	protected $table = '';

	protected $date;
	protected $connection='ogn';

	public function set_date($date)
	{
		$this->date = $date;
		$this->table = 'data' . $date->format('Ymd');
	}
}
