<?php

namespace Modules\Tag\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [

        /**
         * Backend.
         */
        'Modules\Tag\Events\Backend\NewCreated' => [
            'Modules\Tag\Listeners\Backend\NewCreated\UpdateAllOnNewCreated',
        ],
        'App\Events\Backend\UserUpdated' => [
            'App\Listeners\Backend\UserUpdated\UserUpdatedNotifyUser',
        ],

        /**
         * Frontend.
         */
        'App\Events\Auth\UserLoginSuccess' => [
            'App\Listeners\Auth\UpdateLoginData',
        ],
        'App\Events\Frontend\UserRegistered' => [
            'App\Listeners\Frontend\UserRegistered\EmailNotificationOnUserRegistered',
        ],

    ];
}
