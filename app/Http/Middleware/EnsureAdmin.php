<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\RoleHelper;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        RoleHelper::ensureAdmin($request->user());
        return $next($request);
    }
}
