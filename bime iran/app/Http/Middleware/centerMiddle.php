<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class centerMiddle
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


        if(auth::user()){
            if (auth::user()->type=='expert' || auth::user()->type=='admin') {
                return redirect('/');
            }
        }else{
            return redirect('/');
        }

        return $next($request);

    }
}
