<?php

namespace App\Http\Middleware;

use Closure;

class CheckRoleTPTeach
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
                && !$request->user()->hasRole('Сотрудники ТП ГБУ РЦИТ')
                && !$request->user()->hasRole('Учителя')
           ) 
        { 
            return redirect('forbidden');
        }          
        return $next($request);
    }
}
