<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // User tidak boleh admin atau editor
        if ($user->hasRole('admin') || $user->hasRole('editor')) {
            abort(403, 'Unauthorized access. User only.');
        }

        return $next($request);
    }
}
