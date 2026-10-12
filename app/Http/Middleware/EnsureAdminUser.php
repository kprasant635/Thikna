<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminUser
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated administrator request.',
                    'redirect_url' => route('admin.login'),
                ], 401);
            }

            return redirect()->route('admin.login')->with('error', 'Please log in with administrator credentials.');
        }

        $user = Auth::user();

        if (! $user->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Administrator privileges required.',
                ], 403);
            }

            return redirect()->route('admin.login')->with('error', 'Access denied. You do not have administrator permissions.');
        }

        return $next($request);
    }
}
