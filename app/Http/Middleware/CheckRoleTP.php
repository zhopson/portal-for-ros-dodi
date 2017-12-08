<?php

namespace App\Http\Middleware;

use Closure;

class CheckRoleTP
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
        if (!$request->user()->hasRole('Сотрудники ТП ИНТ') 
                && !$request->user()->hasRole('Сотрудники ТП РОС') 
                && !$request->user()->hasRole('Сотрудники ТП ГБУ РЦИТ')) { 
            return redirect('forbidden');
        }           
        return $next($request);
    }
}
