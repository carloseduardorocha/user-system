<?php

namespace App\Http\Middleware;

use Closure;

use App\Helpers\ApiResponse;

use Illuminate\Http\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user)
        {
            return ApiResponse::jsonException(Response::HTTP_UNAUTHORIZED, 'Unauthenticated', class_basename($this));
        }

        return $next($request);
    }
}
