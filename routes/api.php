<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::put('user/settings', [UserController::class, 'settingsUpdate'])
        ->name('settings.update');
});

Route::get('feed', [ArticleController::class, 'feed'])
    ->name('feed');

Route::get('search', [ArticleController::class, 'search'])
    ->name('search');

Route::get('/categories', CategoryController::class);

Route::get('/sources', SourceController::class);

Route::get('/authors', AuthorController::class);
