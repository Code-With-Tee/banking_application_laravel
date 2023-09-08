<?php

namespace App\Providers;

use App\Events\DepositEvent;
use App\Events\TransactionEvent;
use App\Events\TransferEvent;
use App\Events\WithdrawalEvent;
use App\Listeners\DepositListener;
use App\Listeners\TransferListener;
use App\Listeners\WithdrawalListener;
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
        DepositEvent::class => [
            DepositListener::class,
        ],
        WithdrawalEvent::class => [
            WithdrawalListener::class,
        ],
        TransferEvent::class => [
            TransferListener::class,
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
