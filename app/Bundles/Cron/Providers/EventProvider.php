<?php

namespace App\Bundles\Cron\Providers;

use App\Bundles\Cron\Listeners\TotemTaskUpdatedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Studio\Totem\Events\Created as TotemTaskCreated;
use Studio\Totem\Events\Updated as TotemTaskUpdated;
use Studio\Totem\Events\Deleted as TotemTaskDeleted;

final class EventProvider extends EventServiceProvider
{
    protected $listen = [
        TotemTaskCreated::class => [
            TotemTaskUpdatedListener::class
        ],
        TotemTaskUpdated::class => [
            TotemTaskUpdatedListener::class,
        ],
        TotemTaskDeleted::class => [
            TotemTaskUpdatedListener::class,
        ],
    ];
}
