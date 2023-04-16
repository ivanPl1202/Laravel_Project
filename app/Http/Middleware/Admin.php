<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403);
        }
        return $next($request);






























        //Główna metoda "handle" jest wywoływana podczas wykonywania każdego żądania HTTP.
        // Sprawdza ona, czy użytkownik jest zalogowany i czy jest administratorem (pole "is_admin" jest true), za pomocą funkcji "auth()->check()" i "auth()->user()->is_admin".
        //
        //Jeśli nie jest zalogowany lub nie jest administratorem, środkowy warstwa generuje błąd 403 (niedozwolony dostęp).
        // W przeciwnym razie, wywołuje następne środowisko (metodę $next) i przekazuje mu żądanie ($request).
    }
}
