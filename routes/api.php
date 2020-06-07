<?php

Route::group([
    'middleware' => [
        \App\Http\Middleware\CheckPermissions::class,
    ],
], function () {
    require 'apis/api.landing.php';
    require 'apis/api.auth.php';
    require 'apis/api.user.php';
    require 'apis/api.scores.php';
    require 'apis/api.contragents.php';
    require 'apis/api.contracts.php';
    require 'apis/api.supplies.php';
    require 'apis/api.products.php';
    require 'apis/api.documents.php';
    require 'apis/api.vocabs.php';
    require 'apis/api.external_news.php';
    require 'apis/api.variable_definitions.php';
    require 'apis/api.parsers.php';
    require 'apis/api.dialogs.php';
    require 'apis/api.stat.php';
    require(__DIR__ . '/../vendor/dba/controltime/routes/api.php');
    require 'apis/api.employees.php';
    require 'apis/api.controltime.php';
    require 'apis/api.admin_services.php';
    require 'apis/api.logs.php';
});

require 'apis/api.tech_support.php';

