<?php

namespace App\Http\Controllers;

use App\Domain\Settings;
use App\Infrastructure\Cache\Cache;
use App\Infrastructure\Cache\CacheTag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function settingsUpdate(Request $request): JsonResponse
    {
        //TODO:: refactor with repository
        $setting = Settings::query()->updateOrCreate([
            'user_id' => Auth::id(),
        ], [
            'user_id' => Auth::id(),
            'sources' => $request->input('sources', []),
            'categories' => $request->input('categories', []),
            'authors' => $request->input('categories', []),
        ]);

        Cache::flushTagCache(CacheTag::ARTICLE);

        return response()->json(['data' => $setting->toArray()]);
    }
}
