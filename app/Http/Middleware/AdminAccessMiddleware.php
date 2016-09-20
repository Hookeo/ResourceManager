<?php

namespace App\Http\Middleware;

use Closure;

class AdminAccessMiddleware
{
    /**
     * Handle an incoming request.
     * Rejects if regular User, accepts if Admin
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try
        {
            if($request->user()->role == 'Admin')
            {
                return $next($request);
            }
            else
            {
                return response('Unauthorized! Please contact an Admin to access this page.', 401);
            }
        }
        catch(\Exception $e)
        {
            return redirect()->guest('login');
        }
    }
}