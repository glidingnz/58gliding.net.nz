<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Org;
use App\Facades\Messages;

class LoadOrg
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$subdomains = explode(".",$_SERVER['HTTP_HOST']);
		$subdomain = array_shift($subdomains);
		
		$org = Org::where('slug', $subdomain)->first();
		$request->attributes->add(['_ORG' => $org]);
		return $next($request);
	}
}
