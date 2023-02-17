<?php

namespace App\Exceptions;

use App\Http\ResponseHelper;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Exceptions\MissingScopeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            $logMessage = sprintf("%s. Code: %s", $e->getMessage(), $e->getCode());

            $data = [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];

            if (null !== $request = request()) {
                $data['method'] = $request->method();
                $data['path'] = $request->path();
                $data['ip'] = $request->ip();
                $data['params'] = $request->all();
                $data['token'] = $request->bearerToken();
            }

            Log::error($logMessage, $data);
        });

        $this->renderable(function (Throwable $e) {
            return match (get_class($e)) {
                ValidationException::class => ResponseHelper::validationError($e->errors()),
                AuthenticationException::class,
                AuthorizationException::class => ResponseHelper::error(
                    $e->getCode(),
                    $e->getMessage(),
                    Response::HTTP_UNAUTHORIZED
                ),
                AccessDeniedHttpException::class,
                MissingScopeException::class => ResponseHelper::error(
                    $e->getCode(),
                    (! empty($e->getMessage())) ? $e->getMessage() : 'Unauthenticated.',
                    Response::HTTP_UNAUTHORIZED
                ),
                default => ResponseHelper::error(
                    $e->getCode(),
                    $e->getMessage(),
                    Response::HTTP_INTERNAL_SERVER_ERROR
                ),
            };
        });
    }
}
