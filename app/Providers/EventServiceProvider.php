<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\PostCreatedTelegram;
use App\Listeners\PostCreatedTelegramNotification;
use App\Listeners\PostCreatedSubscriber;

/**
 * @property array $listen
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        PostCreatedTelegram::class => [
            PostCreatedTelegramNotification::class,
        ],
    ];

    /**
     * @var array
     */
    protected $subscribe = [
        PostCreatedSubscriber::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        
    }
}
