<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->status !== User::STATUS_ACTIVE) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account requires an active subscription to access this feature.',
                    'redirect_url' => route('subscription.show'),
                ], 403);
            }

            return redirect()->route('subscription.show')->with('warning', 'Please purchase at least 1 course to activate your account and access member benefits.');
        }

        return $next($request);
    }
}
