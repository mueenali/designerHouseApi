<?php

namespace App\Providers;

use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Eloquent\ChatRepository;
use App\Repositories\Eloquent\CommentRepository;
use App\Repositories\Eloquent\DesignRepository;
use App\Repositories\Eloquent\InvitationRepository;
use App\Repositories\Eloquent\MessageRepository;
use App\Repositories\Eloquent\TeamRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Interfaces\IBaseRepository;
use App\Repositories\Interfaces\IChatRepository;
use App\Repositories\Interfaces\ICommentRepository;
use App\Repositories\Interfaces\IDesignRepository;
use App\Repositories\Interfaces\IInvitationRepository;
use App\Repositories\Interfaces\IMessageRepository;
use App\Repositories\Interfaces\ITeamRepository;
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
        $this->app->bind(ICommentRepository::class, CommentRepository::class);
        $this->app->bind(ITeamRepository::class, TeamRepository::class);
        $this->app->bind(IInvitationRepository::class, InvitationRepository::class);
        $this->app->bind(IChatRepository::class, ChatRepository::class);
        $this->app->bind(IMessageRepository::class, MessageRepository::class);
    }
}
