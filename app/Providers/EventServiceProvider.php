<?php

namespace App\Providers;

use App\Events\NewsComment;
use App\Events\NewsCreated;
use App\Events\NewsDeleted;
use App\Events\NewsUpdated;
use App\Events\UserDeleted;
use App\Events\UserRegistered;
use App\Events\UserLogin;
use App\Events\UserUpdate;
use App\Events\UserUpdateAdmin;
use App\Listeners\LogNewsComment;
use App\Listeners\LogNewsCreated;
use App\Listeners\LogNewsDeleted;
use App\Listeners\LogNewsUpdated;
use App\Listeners\LogUserDeleted;
use App\Listeners\LogUserRegistered;
use App\Listeners\LogUserLogin;
use App\Listeners\LogUserUpdate;
use App\Listeners\LogUserUpdateAdmin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        NewsCreated::class => [
            LogNewsCreated::class,
        ],
        NewsUpdated::class => [
            LogNewsUpdated::class,
        ],
        NewsDeleted::class => [
            LogNewsDeleted::class,
        ],
        NewsComment::class => [
            LogNewsComment::class,
        ],
        UserRegistered::class => [
            LogUserRegistered::class,
        ],
        UserLogin::class => [
            LogUserLogin::class,
        ],
        UserUpdate::class => [
            LogUserUpdate::class,
        ],
        UserDeleted::class => [
            LogUserDeleted::class,
        ],
        UserUpdateAdmin::class => [
            LogUserUpdateAdmin::class,
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
