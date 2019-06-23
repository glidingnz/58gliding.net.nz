<?php
namespace App\Facades;
use Illuminate\Support\Facades\Facade;

class Messages extends Facade {
	protected static function getFacadeAccessor() { return 'messages'; } // most likely you want MyClass here
}
