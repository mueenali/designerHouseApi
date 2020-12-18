<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\CommentService;
use App\Services\DesignService;
use App\Services\Interfaces\IAuthService;
use App\Services\Interfaces\ICommentService;
use App\Services\Interfaces\IDesignService;
use App\Services\Interfaces\IInvitationService;
use App\Services\Interfaces\ITeamService;
use App\Services\Interfaces\IUserService;
use App\Services\InvitationService;
use App\Services\TeamService;
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
        $this->app->bind(ICommentService::class, CommentService::class);
        $this->app->bind(ITeamService::class, TeamService::class);
        $this->app->bind(IInvitationService::class, InvitationService::class);
    }
}
