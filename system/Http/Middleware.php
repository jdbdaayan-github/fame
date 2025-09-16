<?php

namespace System\Http\Middleware;

use System\Http\Request;
use System\Http\Response;

interface Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param \System\Http\Request $request
     * @param callable $next  The next middleware or the final destination
     * @return \System\Http\Response
     */
    public function handle(Request $request, callable $next): Response;
}
