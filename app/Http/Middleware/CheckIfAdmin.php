<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CheckIfAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
         
        if ($user->hasAnyRole(['superadmin', 'admin'])) {
           
            return $next($request);
        }
        //Auth::user()->logout();
        return redirect()->route('login');
    }
}
