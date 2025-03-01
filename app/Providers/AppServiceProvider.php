<?php

declare(strict_types = 1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Override;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        $this->callAfterResolving('blade.compiler', fn (BladeCompiler $bladeCompiler) => $this->registerBladeExtensions($bladeCompiler));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //    Gate::define('admin', fn (User $user) => $user->hasPermission('admin'));
    }

    public static function bladeMethodWrapper($method, $role, $guard = null): bool
    {
        return auth($guard)->check() && auth($guard)->user()->{$method}($role);
    }

    protected function registerBladeExtensions(BladeCompiler $bladeCompiler): void
    {
        // permission checks
        $bladeCompiler->if('haspermission', fn (): bool => static::bladeMethodWrapper('hasPermission', ...func_get_args()));
        $bladeCompiler->if('permission', fn (): bool => static::bladeMethodWrapper('hasPermission', ...func_get_args()));
        // role checks
        //        $bladeCompiler->if('role', fn (): bool => static::bladeMethodWrapper('hasRole', ...func_get_args()));
        //        $bladeCompiler->if('hasrole', fn (): bool => static::bladeMethodWrapper('hasRole', ...func_get_args()));
        //        //        $bladeCompiler->if('hasanyrole', fn () => $this->bladeMethodWrapper('hasAnyRole', ...func_get_args()));
        //        //        $bladeCompiler->if('hasallroles', fn () => $this->bladeMethodWrapper('hasAllRoles', ...func_get_args()));
        //        //        $bladeCompiler->if('hasexactroles', fn () => $this->bladeMethodWrapper('hasExactRoles', ...func_get_args()));
        /*        $bladeCompiler->directive('endunlessrole', fn (): string => '<?php endif; ?>');*/
    }
}
