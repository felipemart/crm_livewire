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
    public function handle(Request $request, Closure $next, $permisson): Response
    {
        $permisson = explode('|', $permisson);

        if (static::checkPermission('hasPermission', $permisson)) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }

    public static function checkPermission($method, $permisson, $guard = null): bool
    {
        return auth($guard)->check() && auth($guard)->user()->{$method}($permisson);
    }
}
