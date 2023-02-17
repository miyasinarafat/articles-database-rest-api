<?php

namespace App\Http;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseHelper
{
    /**
     * @param array $data
     * @return JsonResponse
     */
    public static function success(array $data = []): JsonResponse
    {
        return static::renderResponse($data);
    }

    /**
     * @param array $data
     * @return JsonResponse
     */
    public static function createdSuccess(array $data = []): JsonResponse
    {
        return static::renderResponse($data, Response::HTTP_CREATED);
    }

    /**
     * @param int|string $errorCode
     * @param string $message
     * @param int $status
     * @param array $errorsList
     * @return JsonResponse
     */
    public static function error(
        int|string $errorCode,
        string $message,
        int $status = Response::HTTP_NOT_FOUND,
        array $errorsList = []
    ): JsonResponse {
        return static::renderResponse(
            $errorsList,
            $status,
            $message,
            $errorCode
        );
    }

    /**
     * @param string $message
     * @param array $errorsList
     * @return JsonResponse
     */
    public static function validationError(
        array $errorsList,
        string $message = 'Invalid data'
    ): JsonResponse {
        return static::renderResponse(
            $errorsList,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $message,
            1000
        );
    }

    /**
     * @param array $data
     * @param int $status
     * @param string $message
     * @param int|string|null $errorCode
     * @return JsonResponse
     */
    private static function renderResponse(
        array $data = [],
        int $status = 200,
        string $message = '',
        int|string|null $errorCode = null
    ): JsonResponse {
        $response = [];

        $response['status'] = self::getStatusCodeClass($status);
        $response['data'] = $data;

        if ($message) {
            $response['message'] = $message;
        }

        if ($errorCode) {
            $response['errorNo'] = $errorCode;
        }

        return response()->json($response, $status);
    }

    /**
     * @param int $code
     * @return string
     */
    private static function getStatusCodeClass(int $code): string
    {
        $definer = substr($code, 0, 1);

        return match ($definer) {
            '5', '4' => 'error',
            '3' => 'redirect',
            default => 'success',
        };
    }
}
