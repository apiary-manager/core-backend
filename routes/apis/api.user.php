<?php

use Illuminate\Support\Facades\Route;

Route::get('me', 'User\UserController@me');

Route::get('users/page-{page}', 'User\UserController@index');
Route::get('users/{user}/activate', 'User\UserController@activate');
Route::get('users/{user}/deactivate', 'User\UserController@deactivate');
Route::get('users/{user}/detach-role/{role}', 'User\UserController@detachRole');
Route::apiResource('users', 'User\UserController');

// Roles

Route::prefix('roles')->group(function () {
    Route::get('page-{page}', 'User\RoleController@index');
    Route::get('{role}/attach-allowed-for-sign', 'User\RoleController@attachAllowedForSignUp');
    Route::get('{role}/detach-allowed-for-sign', 'User\RoleController@detachAllowedForSignUp');
});

Route::apiResource('roles', 'User\RoleController');

// Permissions

Route::prefix('permissions')->group(function () {
    Route::get('page-{page}', 'User\PermissionController@index');
});

Route::apiResource('permissions', 'User\PermissionController');