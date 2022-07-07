<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->register_repositories();
    }
    
    /**
     * Configure global rate limiter
     *
     * @return void
     */
    public function boot()
    {
        app(\Illuminate\Cache\RateLimiter::class)->for('global', function () {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(3)->by(request()->ip());
        });
        $this->register_repositories();
    }

    private function register_repositories()
    {
        $this->app->bind('App\Repositories\Contracts\IUserRepository', 'App\Repositories\UserRepository');
        $this->app->bind('App\Repositories\Contracts\ISellerRepository', 'App\Repositories\SellerRepository');
        $this->app->bind('App\Repositories\Contracts\IProductRepository', 'App\Repositories\ProductRepository');
        $this->app->bind('App\Repositories\Contracts\ICategoryRepository', 'App\Repositories\CategoryRepository');
    }

}
