<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthRegulateur
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('regulateur_id')) {
            return redirect('/regulateur/login');
        }
        return $next($request);
    }
}