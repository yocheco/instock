<?php

namespace App\Http\Middleware;

use Closure;

class isMine
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
        //extraemos el nombre del recurso
        $name = $request->route()->parameterNames[0];
        //extraemos el id en los parametros
        $id = $request->route()->parameters[$name];
        
        return $next($request);
    }
}
