<?php
namespace App\Http\Middleware;
use Closure;
class SessionTimeout
{
  public function handle($request, Closure $next)
  {
    // If user is not logged in...
    //if (auth()->check()) {
      //return $next($request);
    //}  
    //auth()->logout();
      //$request->session()->invalidate(); 
    return $next($request);
  }
}
?>