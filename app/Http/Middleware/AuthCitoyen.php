<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthCitoyen
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('citoyen_id')) {
            return redirect('/citoyen/auth');
        }
        return $next($request);
    }
}