<?php

namespace App\Providers;

use App\Macros\RouteMacro;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Auth;
use App\CacheManagers\ChildCacheManager;
use App\CacheManagers\FacultyCacheManager;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Carbon::setLocale('tr');
        setlocale(LC_ALL, 'tr_TR.utf8') or setlocale(LC_ALL, 'tr_TR.utf-8');

        view()->composer('admin.parent', function ($view) {
            $view->with([
                'authUser' => Auth::user()
            ]);
        });

        view()->composer('front.parent', function ($view) {
            $view->with([
                'totalChildren'  => ChildCacheManager::count(),
                'totalFaculties' => FacultyCacheManager::count()
            ]);
        });

        RouteMacro::registerMacros();


        Relation::morphMap([
            'children'   => 'App\Models\Children',
            'volunteers' => 'App\Models\Volunteer',
            'users'      => 'App\Models\User',
            'posts'      => 'App\Models\Post',
        ]);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
