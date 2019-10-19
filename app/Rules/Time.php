<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;

class Time implements Rule
{
	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Determine if the validation rule passes.
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 * @return bool
	 */
	public function passes($attribute, $value)
	{
		try {
			Carbon::createFromFormat('H:i', $value);
			return true;
		} catch (\Exception $err) {}

		try {
			Carbon::createFromFormat('ha', $value);
			return true;
		} catch (\Exception $err) {}

		try {
			Carbon::createFromFormat('h:ia', $value);
			return true;
		} catch (\Exception $err) {}

		return false;
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message()
	{
		return 'Time must be in 15:00 format OR 3pm OR 3:30pm.';
	}
}
