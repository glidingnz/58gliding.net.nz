<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Org;
use App\Models\Setting;
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

		// if the root, then it's the GNZ org
		if (env('APP_DOMAIN')==$_SERVER['HTTP_HOST'] || $subdomain=='www') {
			$subdomain = 'gnz';
		}
		
		if ($org = Org::where('slug', $subdomain)->first())
		{
			// get all settings for the org
			$settings = Setting::where('org_id', $org->id)->get();

			$request->attributes->add(['_ORG' => $org]);
		}

		return $next($request);
	}
}
