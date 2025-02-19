<?php

use App\Helpers\Logger;
use App\Helpers\ApiResponse;

use Illuminate\Http\Response;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Laravel\Passport\Http\Middleware\CheckClientCredentials;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        apiPrefix: '',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/',
    )
    ->withMiddleware(function(Middleware $middleware) {
        $middleware->alias([
            'client' => CheckClientCredentials::class
        ]);
    })
    ->withExceptions(function(Exceptions $exceptions) {
        $exceptions->render(function(Throwable $e, $request) {
            if (!config('app.debug'))
            {
                if ($request->expectsJson())
                {
                    $known_errors = [
                        'Unauthenticated.' => Response::HTTP_UNAUTHORIZED,
                        'Unauthenticated'  => Response::HTTP_UNAUTHORIZED,
                    ];

                    Logger::critical(
                        'Generic Exception Errors',
                        [
                            'message' => addslashes($e->getMessage()),
                            'line'    => $e->getLine(),
                            'file'    => $e->getFile(),
                            'code'    => $e->getCode(),
                        ],
                        ['GENERAL_EXCEPTIONS'],
                        $e
                    );

                    return ApiResponse::jsonException(
                        $known_errors[addslashes($e->getMessage())] ?? Response::HTTP_INTERNAL_SERVER_ERROR,
                        addslashes($e->getMessage())                ?? 'Server API Error',
                        class_basename($e)
                    );
                }
            }
        });
    })->create();
