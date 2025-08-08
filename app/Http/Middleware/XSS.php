<?php
namespace App\Http\Middleware;

use Closure;

class XSS
{
public function handle($request, Closure $next)
{
    // List of route names and controller methods to exclude
    $excludedRoutes = [
        'project_document.insertgrid',    // Route name
        'project_document.updategrid',    // Route name
        'SomeController@index',           // Controller@method
    ];

    // Check if route name is excluded
    $routeName = $request->route()?->getName();
    if (in_array($routeName, $excludedRoutes)) {
        return $next($request);
    }

    // Check if controller@method is excluded
    $action = $request->route()?->getActionName(); // e.g., App\Http\Controllers\SomeController@index
    if ($action && in_array(class_basename($action), $excludedRoutes)) {
        return $next($request);
    }

    // Sanitize input to block XSS
    $input = $request->all();

    array_walk_recursive($input, function ($value) {
        if ((string)$value !== strip_tags((string)$value)) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
                response()->json([
                    'error' => 'Input contains disallowed HTML tags.'
                ], 487)
            );
        }
    });
    // Merge sanitized input back (optional)
    $request->merge($input);

    return $next($request);
}


}
