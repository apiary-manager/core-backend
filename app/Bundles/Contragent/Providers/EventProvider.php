<?php

namespace App\Bundles\Contragent\Providers;

use App\Bundles\Contragent\Events\ExternalManagerCreated;
use App\Bundles\Contragent\Listeners\ExternalManagerCreatedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;

final class EventProvider extends EventServiceProvider
{
    protected $listen = [
        ExternalManagerCreated::class => [
            ExternalManagerCreatedListener::class,
        ],
    ];
}
