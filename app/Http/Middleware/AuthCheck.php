<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Vip;
use App\Models\Utilisateur;
use Illuminate\Http\Request;

class AuthCheck
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
        if(!session()->has('utilisateur')){
            return redirect('connexion');
        }
        
        return $next($request);
    }
}
