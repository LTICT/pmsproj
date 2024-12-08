<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class CustomMiddleware
{
	public function construct()
	{

		/*$currentLanguage=session()->get('language');
		var_dump($currentLanguage);
		if(!isset($currentLanguage) || empty($currentLanguage)){
			$currentLanguage='or';
		}
		app()->setLocale($currentLanguage);  */
	}
}