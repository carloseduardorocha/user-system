<?php

namespace App\Services\Api;

use App\Helpers\User;

use App\Contracts\Services\IUserService;

use App\Exceptions\UserNotFoundException;
use App\Exceptions\ErrorOnRestoreUserException;
use App\Exceptions\ErrorOnUpdateUserPasswordException;

use Illuminate\Support\Facades\Hash;

class UserService implements IUserService
{
    public static function me(string $uuid): array
    {
        $user = User::get($uuid);

        if (is_null($user))
        {
            throw new UserNotFoundException();
        }

        return [
            'uuid'  => $user->uuid,
            'name'  => $user->name,
            'email' => $user->email,
        ];
    }

    public static function login(string $email, string $password): ?string
    {
        $user = User::get($email);

        if (is_null($user) || !Hash::check($password, $user->password))
        {
            return null;
        }

        // @@todo return jwt token
        return 'jwt token';
    }

    public static function create(string $email, string $name, string $password): array
    {
        $user = User::create($email, $name, $password);

        return [
            'uuid'  => $user->uuid,
            'name'  => $user->name,
            'email' => $user->email,
        ];
    }

    public static function update(string $uuid, string $email, string $name): void
    {
        $user = User::get($uuid);

        if (is_null($user))
        {
            throw new UserNotFoundException();
        }

        User::update($uuid, $email, $name);
    }

    public static function updatePassword(string $uuid, string $current_password, string $new_password): void
    {
        if (!User::updatePassword($uuid, $current_password, $new_password))
        {
            throw new ErrorOnUpdateUserPasswordException();
        }
    }

    public static function delete(string $uuid): void
    {
        $user = User::get($uuid);

        if (is_null($user))
        {
            throw new UserNotFoundException();
        }

        User::delete($uuid);
    }

    public static function restore(string $uuid): void
    {
        if (!User::restore($uuid))
        {
            throw new ErrorOnRestoreUserException();
        }
    }
}
