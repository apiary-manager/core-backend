<?php

namespace App\Bundles\User\Providers;

use App\Based\Contracts\RouteServiceProvider;

final class RouteProvider extends RouteServiceProvider
{
    protected $namespace = 'App\Bundles\User\Http\Controllers';

    protected $routesApiFile = __DIR__ . '/../Http/Routes/api.php';
}
