<?php

namespace App\Bundles\Contragent\Providers;

use Illuminate\Support\ServiceProvider;
use App\Bundles\Contragent\Support\DaDataClient;
use GuzzleHttp\Client;

class ContragentProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DaDataClient::class, function () {
            return new DaDataClient(new Client([
                'base_uri' => 'https://suggestions.dadata.ru/suggestions/api/4_1/',
            ]), 'bd0f0bb6afa265cda47baacbdb7bdd4c077ffc64');
        });

        $this->app->register(RouteProvider::class);
    }
}
