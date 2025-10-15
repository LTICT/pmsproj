<?php
namespace App\Http\Middleware;

use Closure;

class XSS
{
public function handle($request, Closure $next)
{
    // List of route names and controller methods to exclude
    $excludedRoutes = [
        //'project_monitoring_evaluation.insertgrid',    // Route name
        //'project_monitoring_evaluation.updategrid',    // Route name
        'PmsprojectmonitoringevaluationController@listgrid',           // Controller@method
        'PmsprojectmonitoringevaluationController@insertgrid',           // Controller@method
        'PmsprojectmonitoringevaluationController@updategrid',           // Controller@method
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
    // Detect only <script> tags (opening or closing, any attributes)
    if (preg_match('/<\s*\/?\s*script\b/i', (string)$value)) {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json([
                'error' => 'Input contains disallowed <script> tags.'
            ], 487)
        );
    }
});
    // Merge sanitized input back (optional)
    $request->merge($input);

    return $next($request);
}


}
