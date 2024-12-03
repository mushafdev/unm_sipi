<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticationCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard = null): Response
    {
        if($guard=='admin'){
            if (!Auth::check()) {
                abort(403,"",$header=['url'=>'xxxxx']);
            }
        }
        if($guard=='mahasiswa'){
            if (!Auth::guard('mahasiswa')->check()) {
                abort(403,"",$header=['url'=>'xxxxx']);
            }
        }


        if($guard=='multi'){
            if (Auth::check() || Auth::guard('mahasiswa')->check()) {
                return $next($request);
            }

            abort(403,"",$header=['url'=>'xxxxx']);
        }
        
        return $next($request);

    }
}
