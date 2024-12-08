<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\DB;
class SetLanguage
{
	public function handle($request, Closure $next)
	{
		$selectedLocale=session()->get('selectedLanguage');
		app()->setLocale($selectedLocale);
		return $next($request);
	}
}