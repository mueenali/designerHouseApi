<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\DesignService;
use App\Services\Interfaces\IAuthService;
use App\Services\Interfaces\IDesignService;
use App\Services\Interfaces\IUserService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class ServicesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(IDesignService::class, DesignService::class);
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IAuthService::class, AuthService::class);
    }
}