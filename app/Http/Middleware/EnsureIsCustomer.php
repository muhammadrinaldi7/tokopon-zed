<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ensures only customer/user roles can access customer-specific routes.
 * Admin/superadmin users will be redirected to admin dashboard.
 */
class EnsureIsCustomer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // If the user has admin/superadmin role (and NOT a regular 'user'), redirect to admin
        if ($user->hasAnyRole(['admin', 'superadmin']) && !$user->hasRole('user')) {
            return redirect('/admin/dashboard')->with('error', 'Halaman ini hanya untuk customer.');
        }

        return $next($request);
    }
}
