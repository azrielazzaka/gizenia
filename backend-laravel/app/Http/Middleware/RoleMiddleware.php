<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        $user = auth('api')->user();

        // Cek apakah user sedang login dan role-nya sesuai dengan yang diminta
        if (!$user || $user->role !== $role) {
            return response()->json([
                'error' => 'Akses ditolak. Anda bukan ' . $role
            ], 403);
        }

        return $next($request);
    }
}