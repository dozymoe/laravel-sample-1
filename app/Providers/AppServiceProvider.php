<?php

namespace App\Providers;

use App\Contracts\CompanyRepository;
use App\Contracts\CompanyUserRepository;
use App\Contracts\UserRepository;
use App\Repositories\CompanyRepositoryEloquent;
use App\Repositories\CompanyUserRepositoryEloquent;
use App\Repositories\UserRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CompanyRepository::class,
            CompanyRepositoryEloquent::class);
        $this->app->bind(CompanyUserRepository::class,
            CompanyUserRepositoryEloquent::class);
        $this->app->bind(UserRepository::class, UserRepositoryEloquent::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
