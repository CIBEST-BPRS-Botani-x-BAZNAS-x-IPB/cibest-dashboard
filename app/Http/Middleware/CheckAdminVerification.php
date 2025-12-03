<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Redirect;

class CheckAdminVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Check if the user has been verified by an admin
            if (!$user->isAdminVerified()) {
                // If not verified, show appropriate message based on status
                if ($user->isRejected()) {
                    // User was rejected
                    Auth::logout();
                    if ($request->expectsJson()) {
                        return response()->json(['message' => 'Your account has been rejected by an administrator.'], 403);
                    }
                    return Redirect::route('login')->with('status', 'Your account has been rejected by an administrator.');
                } else {
                    // User is still pending verification
                    if ($request->expectsJson()) {
                        return response()->json(['message' => 'Your account is pending admin verification.'], 403);
                    }
                    return Redirect::route('verification.pending')->with('status', 'Your account is pending admin verification. Please wait for an administrator to verify your account.');
                }
            }
        }

        return $next($request);
    }
}
