<?php

namespace App\Http\Middleware;

use Fideloper\Proxy\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array|string|null
     */
  protected $proxies;
    protected $headers = Request::HEADER_X_FORWARDED_ALL;

    public function __construct()
    {
        $this->proxies = '*'; // Adjust this as needed
    }
    public function handle($request, Closure $next)
    {
        $response = parent::handle($request, $next);

        // Add XSS protection header
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        // Optional: Add CSP as well for extra XSS protection
        // $response->headers->set('Content-Security-Policy', "default-src 'self'");

        return $response;
    }
}
