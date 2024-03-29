<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Based\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Based\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Based\Exceptions\Handler::class
);

$app->singleton(
    \App\Bundles\Admin\Contracts\LogRepositoryInterface::class,
    \App\Bundles\Admin\Repositories\LogRepository::class
);

$app->singleton(
    \App\Bundles\Admin\Contracts\LogReaderInterface::class,
    \App\Bundles\Admin\Support\LogReader::class
);

$app->singleton(
    \App\Bundles\Admin\Services\LogService::class,
);
$app->singleton(
    \ArtARTs36\PushAllSender\Interfaces\PusherInterface::class,
    function () {
        return new \ArtARTs36\PushAllSender\Senders\PushAllSender(env('PUSHALL_CHANNEL_ID'), env('PUSHALL_API_KEY'));
    }
);

$app->bind(
    \ArtARTs36\ShellCommand\Interfaces\ShellCommandInterface::class,
    \ArtARTs36\ShellCommand\ShellCommand::class
);

$app->singleton(\App\Bundles\Employee\Services\TimeReportService::class);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
