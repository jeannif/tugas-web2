<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log; 

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/')->withErrors([
            
            'email' => 'Anda harus login terlebih dahulu']);
        }




        $roleName = Role::find(Auth::user()->role_id)->name;
        if (!in_array($roleName, $roles)) {

            return back();
        }
        return $next($request);
    }
}
