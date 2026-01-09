<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * Trust all proxies (Railway / reverse proxy).
     */
    protected $proxies = '*';

    /**
     * Use forwarded headers (so Laravel detect HTTPS correctly).
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
