<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Contracts\Providers\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth as AuthRole;
use App\Traits\ResponseTrait;

class CheckRoleUser
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(AuthRole::user()->role === 'admin'){
            return $next($request);
        }
        return $this->failedWithErrors(500, 'Permission denied. You are not an admin.');
    }
}
