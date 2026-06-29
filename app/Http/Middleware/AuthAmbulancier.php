<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthAmbulancier
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('ambulancier_id')) {
            return redirect('/ambulancier/login');
        }
        return $next($request);
    }
}