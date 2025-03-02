<?php

declare(strict_types = 1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permisson): Response
    {
        $permisson = explode('|', $permisson);

        if (static::checkPermission('hasPermission', $permisson)) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }

    /**
     * @param string $method
     * @param array<string> $permissons
     * @param string|null $guard
     * @return bool
     */
    public static function checkPermission(string $method, array $permissons, null | string $guard = null): bool
    {
        return auth($guard)->check() && auth($guard)->user()->{$method}($permissons);
    }
}
