<?php
namespace App\Http\Middleware;

use Closure;

class XSS
{
public function handle($request, Closure $next)
{
    // List of route names or controller actions to exclude
    $excludedRoutes = [
        'ggggg',                      // Route name
        'SomeController@index',       // Controller@method
    ];

    // Option 1: Check route name
    if (in_array($request->route()?->getName(), $excludedRoutes)) {
        return $next($request);
    }

    // Option 2: Check controller and method
    $action = $request->route()?->getActionName(); // e.g. App\Http\Controllers\SomeController@index
    if (in_array(class_basename($action), $excludedRoutes)) {
        return $next($request);
    }

    // Perform XSS filtering
    $input = $request->all();

    foreach ($input as $key => $value) {
        if (is_array($value)) {
            array_walk_recursive($value, function ($v) {
                if ($v !== strip_tags($v)) {
                    throw new \Illuminate\Http\Exceptions\HttpResponseException(
                        response()->json([
                            'error' => 'Input contains disallowed HTML tags.'
                        ], 487)
                    );
                }
            });
        } else {
            if ($value !== strip_tags($value)) {
                return response()->json([
                    'error' => 'Input contains disallowed HTML tags.'
                ], 487);
            }
        }
    }

    return $next($request);
}

}
