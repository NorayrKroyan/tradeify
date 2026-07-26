<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->hasApprovedCompany()) {
            return redirect()->route('verification.status')
                ->with('error', 'Your company must be approved before using this feature.');
        }

        return $next($request);
    }
}
