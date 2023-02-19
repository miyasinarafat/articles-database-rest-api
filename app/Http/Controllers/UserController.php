<?php

namespace App\Http\Controllers;

use App\Domain\SettingsFactory;
use App\Domain\SettingsRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class UserController extends Controller
{
    public function __construct(
        private readonly SettingsRepositoryInterface $settingsRepository,
    ) {
    }

    /**
     * Update the specified resource in storage.
     */
    public function settingsUpdate(Request $request): JsonResponse
    {
        $settings = SettingsFactory::fromArray(array_merge($request->all(), ['user_id' => Auth::id()]));

        $dbSettings = $this->settingsRepository->update($settings);

        return response()->json(['data' => $dbSettings?->toArray()]);
    }
}
