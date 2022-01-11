<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    $router->group(['prefix' => 'users'], function (Router $router) {
        $router->resource('/', UserController::class);
        $router->resource('/roles', RoleController::class);
        $router->resource('/permissions', PermissionController::class);
        $router->resource('/locales', LocaleController::class);
    });
});
