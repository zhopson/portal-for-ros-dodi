<?php

namespace App\Http\Middleware;

use Closure;

class setDebug
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()) {
            if ($request->user()->email === 'man24@yandex.ru') {
                config(['app.debug' => true]); 
            }        
            else { config(['app.debug' => false]); }
        }
        return $next($request);
    }
}
