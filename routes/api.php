<?php

use App\Domain\Author\Author;
use App\Domain\Category\Category;
use App\Domain\Source\Source;
use App\Http\Controllers\ArticleController;
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

Route::get('/categories', function () {
    //TODO:: refactor with repository
    $categories = Category::query()->get()->toArray();

    return response()->json(['data' => $categories]);
});

Route::get('/sources', function () {
    //TODO:: refactor with repository
    $sources = Source::query()->get()->toArray();

    return response()->json(['data' => $sources]);
});

Route::get('/authors', function () {
    //TODO:: refactor with repository
    $authors = Author::query()->get()->toArray();

    return response()->json(['data' => $authors]);
});
