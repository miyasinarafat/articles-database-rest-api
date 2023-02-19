<?php

namespace App\Http\Controllers;

use App\Domain\Category\CategoryRepositoryInterface;
use App\Http\ResponseHelper;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): JsonResponse
    {
        /** @var CategoryRepositoryInterface $categoryRepository */
        $categoryRepository = resolve(CategoryRepositoryInterface::class);

        return ResponseHelper::success($categoryRepository->getList()->toArray());
    }
}
