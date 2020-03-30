<?php

use Illuminate\Support\Facades\Route;

Route::get('signup-roles', 'User\RoleController@getRolesForSignUp');
Route::post('signup', 'Auth\RegisterController@store');

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'Auth\AuthController@login');
    Route::post('token/revoke', 'Auth\AuthController@revokeToken');
    Route::post('refresh', 'Auth\AuthController@refresh');
    Route::post('token/issue', 'Auth\AuthController@issueToken');
    Route::post('token/refresh', 'Auth\AuthController@refreshToken');
});

require_once 'apis/api.user.php';

// API для для работы с контрагентами

Route::get(
    'contragents/sync-with-external-system/{contragent}',
    'Contragent\ContragentController@syncWithExternalSystem'
);
Route::get('contragents/page-{page}', 'Contragent\ContragentController@index');
Route::get('contragents/live-find/{term}', 'Contragent\ContragentController@liveFind');
Route::apiResource('contragents', 'Contragent\ContragentController');
Route::get('contragents/find-external-by-inn/{inn}', 'Contragent\ContragentController@findInExternalNetworkByInn');
Route::apiResource('contragent-groups', 'Contragent\ContragentGroupController');
Route::apiResource('contragent-managers', 'Contragent\ContragentManagerController');

// API Для работы с договорами

Route::get('contracts/page-{page}', 'Contract\ContractController@index');
Route::apiResource('contracts', 'Contract\ContractController');
Route::get('contracts/find-by-customer/{customerId}', 'Contract\ContractController@findByCustomer');
Route::apiResource('contract-templates', 'Contract\ContractTemplateController');
Route::apiResource('my-contragents', 'Contragent\MyContragentController');

// API для поставок

Route::get('supplies/page-{page}', 'Supply\SupplyController@index');
Route::apiResource('supplies', 'Supply\SupplyController');
Route::get('supplies/find-by-customer/{customerId}', 'Supply\SupplyController@findByCustomer');

// API для товаров

Route::get('products/top-chart', 'Product\ProductController@topChart');
Route::get('products/page-{page}', 'Product\ProductController@index');
Route::apiResource('products', 'Product\ProductController');

//

Route::apiResource('vocab/size-of-units', 'Vocab\SizeOfUnitController');

// API Для счетов на оплату

Route::prefix('score-for-payments')->group(function () {
    Route::get('page-{page}', 'Supply\ScoreForPaymentController@index');
});

Route::apiResource('score-for-payments', 'Supply\ScoreForPaymentController');
Route::get(
    'score-for-payments/download-by-supply/{supplyId}',
    'Supply\ScoreForPaymentController@checkOrCreateDocumentBySupply'
);
Route::post(
    'score-for-payments/check-document-of-many',
    'Supply\ScoreForPaymentController@checkOrCreateDocumentOfManyScores'
);

//

Route::get('/generate-document/{supply}/{typeId}', 'Document\DocumentGenerateController@generate');
Route::apiResource('documents', 'Document\DocumentController');
Route::get('documents/{id}/download', 'Document\DocumentController@download');

Route::apiResource('product-transport-waybills', 'Supply\ProductTransportWaybillController');

//

Route::get('vocab-quantity-units/page-{page}', 'Vocab\VocabQuantityUnitController@index');
Route::apiResource('vocab-quantity-units', 'Vocab\VocabQuantityUnitController');

//

Route::get('vocab-gos-standards/page-{page}', 'Vocab\VocabGosStandardController@index');
Route::apiResource('vocab-gos-standards', 'Vocab\VocabGosStandardController');

//

Route::get('vocab-banks/page-{page}', 'Vocab\VocabBankController@index');
Route::apiResource('vocab-banks', 'Vocab\VocabBankController');

// API для справочника "Курсы валют"

Route::get('vocab-currencies/page-{page}', 'Vocab\VocabCurrencyController@index');
Route::apiResource('vocab-currencies', 'Vocab\VocabCurrencyController');
Route::get('vocab/currency-courses', 'Vocab\CurrencyCourseController@chart');

// API для ExternalNews

Route::get('external-news/page-{page}', 'News\ExternalNewsController@index');
Route::get('external-news/chart', 'News\ExternalNewsController@chart');
Route::get('external-news/chart/{count}', 'News\ExternalNewsController@chart');
Route::apiResource('external-news', 'News\ExternalNewsController');

// API для статистики

Route::get('stat/general', 'Stat\StatController@general');

//

Route::apiResource(
    'text-data-parser-components',
    'TextDataParser\TextDataParserComponentController'
);

//

Route::prefix('variable-definitions')->group(function () {
    Route::get('page-{page}', 'VariableDefinitionController@index');
});

Route::apiResource('variable-definitions', 'VariableDefinitionController');
