<?php

namespace App\Http\Controllers;

use App\Domain\Source\SourceRepositoryInterface;
use App\Http\ResponseHelper;
use Illuminate\Http\JsonResponse;

class SourceController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): JsonResponse
    {
        /** @var SourceRepositoryInterface $sourceRepository */
        $sourceRepository = resolve(SourceRepositoryInterface::class);

        return ResponseHelper::success($sourceRepository->getList()->toArray());
    }
}
