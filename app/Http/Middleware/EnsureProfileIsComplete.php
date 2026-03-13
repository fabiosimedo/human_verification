<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileIsComplete
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        $isComplete =
            filled($user->name) &&
            filled($user->phone) &&
            $user->primaryPublicImage()->exists();

        if ($isComplete) {
            return $next($request);
        }

        if ($request->routeIs('dashboard.profile.*') || $request->routeIs('logout')) {
            return $next($request);
        }

        return redirect()
            ->route('dashboard.profile.edit')
            ->withErrors([
                'general' => 'Complete seu perfil público para acessar o dashboard.',
            ]);
    }
}