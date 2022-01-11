<?php

use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\Auth\ChangePassword;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CalendarEventController;
use App\Http\Controllers\ClientStatisticController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersCompanyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [RegisterController::class, 'index']);
Route::post('/login', [ 'as' => 'login', 'uses' => '\App\Http\Controllers\Auth\LoginController@index']);
;
Route::post('/recover-password', [ResetPasswordController::class, 'index'])
    ->name('password.recover');
Route::get('/logout', function (Request $request) {
    $request->user()->token()->revoke();
})->middleware('auth:api');

Route::post('/change-password', [ChangePassword::class, 'index'])
    ->middleware('guest:api');

//Route::middleware('auth:api')->group(function () {
    Route::get('/countries/{locale?}', [CompanyController::class, 'countriesByLocale']);
    Route::get('/languages', [CompanyController::class, 'languages']);

    Route::apiResource('affiliates', AffiliateController::class);
    Route::apiResource('departments', DepartmentController::class);

    Route::apiResource('users', UserController::class)
        ->except(['store']);
    Route::apiResource('user', UsersCompanyController::class);

    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('courses', CourseController::class);
    Route::post('courses/{course}/logo', [CourseController::class, 'saveLogo']);
    Route::delete('files/floarla', FileController::class . '@floarlaDelete');
    Route::apiResource('files', FileController::class)
        ->except(['show', 'update']);

    // ME
    Route::group(['prefix' => 'me', 'as' => 'me.'], function () {
        Route::get('/', [UserController::class, 'show'])->name('show');
        Route::put('/', [UserController::class, 'update'])->name('update');
        Route::post('/', [UserController::class, 'uploadAvatar'])->name('upload');
    });

    /// CALENDARS
    Route::group(['prefix' => 'calendar', 'as' => 'calendar.'], function () {
        Route::group(['prefix' => 'event', 'as' => 'event.'], function () {
            Route::get('/{id}', [CalendarEventController::class, 'show']);
            Route::post('/', [CalendarEventController::class, 'store']);
            Route::put('/{id}', [CalendarEventController::class, 'update']);
            Route::delete('/{id}', [CalendarEventController::class, 'delete']);
            Route::delete('/unsubscribe/{id}', [CalendarEventController::class, 'unsubscribe']);
        });

        Route::get('/', [CalendarController::class, 'show']);
    });

    /// COMPANY CONFIGS
    Route::group(['prefix' => 'company', 'as' => 'configs.'], function () {
        Route::get('/{id}/config', [CompanyController::class, 'getConfig']);
        Route::patch('/{id}/config', [CompanyController::class, 'updateConfigs']);
        Route::post('/{id}/config', [CompanyController::class, 'uploadLogo']);

        Route::group(['prefix' => 'pages', 'as' => 'pages.'], function () {
            Route::post('/', [CompanyController::class, 'createPageConfig']);
            Route::post('/{id}', [CompanyController::class, 'uploadIcon']);
            Route::put('/{id}', [CompanyController::class, 'updatePageConfig']);
            Route::delete('/{id}', [CompanyController::class, 'deleteConfigs']);
        });
    });

    /// PERMISSIONS
    Route::group(['prefix' => 'permissions', 'as' => 'permissions.'], function () {
        Route::get('/', [PermissionController::class, 'index']);
        Route::get('/self', [PermissionController::class, 'selfPermissions']);
        Route::put('/', [PermissionController::class, 'syncRolePermission']);
    });

    Route::group(['prefix' => 'statistics', 'as' => 'statistics'], function () {
        Route::get('/', [ClientStatisticController::class, 'index']);
    });
//});
