<?php

namespace App\Http\Controllers;

use App\Domain\Author\AuthorRepositoryInterface;
use App\Http\ResponseHelper;
use Illuminate\Http\JsonResponse;

class AuthorController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): JsonResponse
    {
        /** @var AuthorRepositoryInterface $authorRepository */
        $authorRepository = resolve(AuthorRepositoryInterface::class);

        return ResponseHelper::success($authorRepository->getList()->toArray());
    }
}
