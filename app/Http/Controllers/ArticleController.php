<?php

namespace App\Http\Controllers;

use App\Domain\Article\ArticleRepositoryInterface;
use App\Domain\Objects\ArticleFilterItem;
use App\Domain\Objects\ArticleOrderItem;
use App\Domain\Settings;
use App\Domain\SettingsRepositoryInterface;
use App\Http\Resources\ArticleCollection;
use App\Http\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final class ArticleController extends Controller
{
    public function __construct(
        private readonly ArticleRepositoryInterface $articleRepository,
        private readonly SettingsRepositoryInterface $settingsRepository,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function feed(Request $request): JsonResponse
    {
        $filter = [];
        if (Auth::check()) {
            /** @var Settings $setting */
            $setting = $this->settingsRepository->getByUserId(Auth::id());
            $filter['categories'] = $setting->categories ?? [];
            $filter['sources'] = $setting->sources ?? [];
            $filter['authors'] = $setting->authors ?? [];
        }

        $articleFilter = ArticleFilterItem::fromArray($filter);
        $articles = $this->articleRepository->getList(
            filterItems: $articleFilter,
            page: $request->input('page', 1),
            perPage: $request->input('perPage', 10)
        );

        return ResponseHelper::success(
            ArticleCollection::make($articles)->toArray($request)
        );
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function search(Request $request): JsonResponse
    {
        $order = ArticleOrderItem::fromRequest($request);
        $filter = ArticleFilterItem::fromRequest($request);

        $articles = $this->articleRepository->searchList(
            filterItems: $filter,
            orderItems: $order,
            query: $request->input('query'),
            page: $request->input('page', 1),
            perPage: $request->input('perPage', 10)
        );

        return ResponseHelper::success(
            ArticleCollection::make($articles)->toArray($request)
        );
    }
}
