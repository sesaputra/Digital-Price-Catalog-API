<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenant
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if (!$user->tenant_id) {
            return response()->json([
                'message' => 'User tidak memiliki tenant.',
            ], 403);
        }

        if (!$user->tenant || !$user->tenant->is_active) {
            return response()->json([
                'message' => 'Tenant tidak aktif.',
            ], 403);
        }

        // Simpan tenant yang sedang aktif
        app()->instance('currentTenant', $user->tenant);

        return $next($request);
    }
}