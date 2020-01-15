<?php

namespace App\Http\Middleware;

use Closure;

class Appid
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
    //       $data=request()->post();
    //       print_r($data);die;
        return $next($request);
    }
}
