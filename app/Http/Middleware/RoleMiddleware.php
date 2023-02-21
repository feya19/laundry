<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try{
            $permission = config('permission')[auth()->user()->role];
            $authClass = $permission['master'] + $permission['feature'];
            $actionClass = explode('@', $request->route()->action['controller'])[0];
            $key = array_search($actionClass, config('app.class'));
            return in_array($key, $authClass) ? $next($request) : abort(401);
        }catch(Exception $e){
            abort(401);
        }
    }
}
