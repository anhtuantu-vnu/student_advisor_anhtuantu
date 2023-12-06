<?php

namespace App\Http\Middleware;

use App\Http\Controllers\_CONST;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->role == _CONST::ADMIN_ROLE) {
            return $next($request);
        }
        return redirect('/');
    }
}
