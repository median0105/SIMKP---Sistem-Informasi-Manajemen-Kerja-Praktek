<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\PeriodeService;

class CheckPeriodeAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Check if user is authenticated
        if (!$user) {
            return $next($request);
        }

        // SuperAdmin always has access - skip periode check
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        $access = PeriodeService::canAccessKPSystem($user);

        if (!$access['can_access']) {
            // For API/JSON requests
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => $access['message'],
                    'can_access' => false
                ], 403);
            }

            // For web requests - only redirect if not already on dashboard
            if (!$request->is('dashboard') && !$request->is('dashboard/*')) {
                return redirect()->route('dashboard')
                    ->with('error', $access['message']);
            }
            
            // If already on dashboard, just continue but show message
            if (session()->has('error')) {
                session()->keep('error');
            }
        }

        return $next($request);
    }
}