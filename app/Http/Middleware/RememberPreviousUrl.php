<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RememberPreviousUrl
{
    public function handle(Request $request, Closure $next)
    {
        // Simpan URL sebelumnya ke session, dengan pengecualian untuk:
        // - request AJAX
        // - method selain GET
        // - URL yang sama seperti sebelumnya
        // - route tertentu (misalnya login, logout)

        if ($request->method() === 'GET' &&
            !$request->ajax() &&
            !$request->is('login', 'logout') &&
            $request->fullUrl() !== url()->previous()
        ) {
            session(['back_url' => url()->previous()]);
        }

        return $next($request);
    }
}
