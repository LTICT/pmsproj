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
}
