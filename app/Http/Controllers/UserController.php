<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;

use App\Services\Api\UserService;

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
    /**
     * User
     *
     * @var array<mixed>
     */
    protected $user;

    public function __construct()
    {
        $this->user = (array) auth('token')->user();

        if (!isset($this->user['uuid']))
        {
            throw new InvalidUUIDException();
        }
    }

    public function me(): JsonResponse
    {
        $user_info = UserService::me($this->user['uuid']); // @phpstan-ignore-line

        return ApiResponse::jsonSuccess(new JsonResource($user_info), Response::HTTP_OK, 'User information retrieved successfully!');
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $data  = $request->validated();
        $token = UserService::login($data['email'], $data['password']); // @phpstan-ignore-line

        if (is_null($token))
        {
            return ApiResponse::jsonError(new JsonResource([]), Response::HTTP_UNAUTHORIZED, 'Invalid credentials.');
        }

        return ApiResponse::jsonSuccess(new JsonResource(['token' => $token]), Response::HTTP_OK, 'Login successful!');
    }

    public function create(CreateUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = UserService::create($data['email'], $data['name'], $data['password']); // @phpstan-ignore-line

        return ApiResponse::jsonSuccess(new JsonResource($user), Response::HTTP_OK, 'User created successfully!');
    }

    public function update(UpdateUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        UserService::update($this->user['uuid'], $data['email'], $data['name']); // @phpstan-ignore-line

        return ApiResponse::jsonSuccess(new JsonResource([]), Response::HTTP_OK, 'User updated successfully!');
    }

    public function updatePassword(UpdateUserPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        UserService::updatePassword($this->user['uuid'], $data['current_password'], $data['new_password']); // @phpstan-ignore-line

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
}
