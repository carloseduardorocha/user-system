<?php

namespace App\Http\Controllers;

use App\Enums\TTL;

use App\Helpers\ApiResponse;

use App\Services\UserService;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\RestoreUserRequest;
use App\Http\Requests\UpdateUserPasswordRequest;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

use App\Exceptions\Controllers\InvalidUUIDException;

class UserController extends Controller
{
    public function me(): JsonResponse
    {
        $user = auth('jwt')->user();

        if (!isset($user['uuid']))
        {
            throw new InvalidUUIDException();
        }

        $user_info = UserService::me($user['uuid']); // @phpstan-ignore-line

        return ApiResponse::jsonSuccess(new JsonResource($user_info), Response::HTTP_OK, 'User information retrieved successfully!');
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $token       = auth('jwt')->attempt($credentials);

        if (!$token)
        {
            return ApiResponse::jsonError(new JsonResource([]), Response::HTTP_UNAUTHORIZED, 'Unauthorized.');
        }

        return ApiResponse::jsonSuccess(new JsonResource($this->formatToken((string) $token)), Response::HTTP_OK, 'Login successful!');
    }

    public function create(CreateUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = UserService::create($data['email'], $data['name'], $data['password']); // @phpstan-ignore-line

        return ApiResponse::jsonSuccess(new JsonResource($user), Response::HTTP_OK, 'User created successfully!');
    }

    public function update(UpdateUserRequest $request): JsonResponse
    {
        $user = auth('jwt')->user();

        if (!isset($user['uuid']))
        {
            throw new InvalidUUIDException();
        }

        $data = $request->validated();
        UserService::update($user['uuid'], $data['email'], $data['name']); // @phpstan-ignore-line

        return ApiResponse::jsonSuccess(new JsonResource([]), Response::HTTP_OK, 'User updated successfully!');
    }

    public function updatePassword(UpdateUserPasswordRequest $request): JsonResponse
    {
        $user = auth('jwt')->user();

        if (!isset($user['uuid']))
        {
            throw new InvalidUUIDException();
        }

        $data = $request->validated();
        UserService::updatePassword($user['uuid'], $data['current_password'], $data['new_password']); // @phpstan-ignore-line

        return ApiResponse::jsonSuccess(new JsonResource([]), Response::HTTP_OK, 'Password updated successfully!');
    }

    public function delete(DeleteUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        UserService::delete($data['uuid']); // @phpstan-ignore-line

        return ApiResponse::jsonSuccess(new JsonResource([]), Response::HTTP_OK, 'User deleted successfully!');
    }

    public function restore(RestoreUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = UserService::restore($data['uuid']); // @phpstan-ignore-line

        return ApiResponse::jsonSuccess(new JsonResource($user), Response::HTTP_OK, 'User restored successfully!');
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return array<mixed>
     */
    protected function formatToken($token): array
    {
        return [
            'token'      => $token,
            'type'       => 'bearer',
            'expires_in' => auth('jwt')->factory()->getTTL() * TTL::JWT_TOKEN->value // @phpstan-ignore-line
        ];
    }
}
