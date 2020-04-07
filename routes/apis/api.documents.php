<?php

use Illuminate\Support\Facades\Route;

Route::get('/archives/{timestamp}/{name}', 'Document\ArchiveController@download');

Route::get('/generate-document/{supply}/{typeId}', 'Document\DocumentGenerateController@generate');
Route::post('/generate-documents/{supply}/', 'Document\DocumentGenerateController@generateManyTypes');
Route::apiResource('documents', 'Document\DocumentController');
Route::get('documents/{id}/download', 'Document\DocumentController@download');

Route::apiResource('product-transport-waybills', 'Supply\ProductTransportWaybillController');