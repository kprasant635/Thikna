<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsCertified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->isCertified()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Complete all 3 selected educational courses to unlock your certificate and member benefits.',
                    'redirect_url' => route('courses.index'),
                ], 403);
            }

            return redirect()->route('courses.index')->with('warning', 'Please complete all your selected courses to unlock your certificate and benefits.');
        }

        return $next($request);
    }
}
