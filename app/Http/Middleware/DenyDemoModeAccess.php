<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DenyDemoModeAccess
{
    /**
     * Block access to routes/actions that should not be available in demo mode.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $isDemoMode = (bool) $request->session()->get('demo_mode', false);

        if ($isDemoMode || $user?->hasRole('demo-admin')) {
            abort(403, 'Demo mode is read-only. This action is not available.');
        }

        return $next($request);
    }
}
