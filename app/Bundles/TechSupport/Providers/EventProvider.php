<?php

namespace App\Bundles\TechSupport\Providers;

use App\Bundles\TechSupport\Events\ReportCreated;
use App\Bundles\TechSupport\Listeners\TechSupportReportCreatedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;

final class EventProvider extends EventServiceProvider
{
    protected $listen = [
        ReportCreated::class => [
            TechSupportReportCreatedListener::class,
        ],
    ];
}
