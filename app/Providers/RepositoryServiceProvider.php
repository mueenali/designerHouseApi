<?php

namespace App\Providers;

use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Eloquent\DesignRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\IBaseRepository;
use App\Repositories\Interfaces\IDesignRepository;
use App\Repositories\Interfaces\IUserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
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
        $this->app->bind(IDesignRepository::class, DesignRepository::class);
        $this->app->bind(IBaseRepository::class, BaseRepository::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
    }
}
