<?php

namespace App\Helpers;

use App\Models\User as Model;
use Illuminate\Support\Facades\Hash;

class User
{
    /**
     * Get a user by UUID or email.
     *
     * @param string $identifier UUID or email.
     * @return Model|null
     */
    public static function get(string $identifier): ?Model
    {
        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL)
            ? Model::EMAIL
            : Model::UUID;

        return Model::where($field, $identifier)->first();
    }

    /**
     * Create a new user.
     *
     * @param string $email
     * @param string $name
     * @param string $password
     *
     * @return Model
     */
    public static function create(string $email, string $name, string $password): Model
    {
        return Model::create([
            Model::UUID     => \Illuminate\Support\Str::uuid()->toString(),
            Model::NAME     => $name,
            Model::EMAIL    => $email,
            Model::PASSWORD => Hash::make($password),
        ]);
    }

    /**
     * Update a user's information by UUID.
     *
     * @param string $uuid
     * @param string $email
     * @param string $name
     *
     * @return bool
     */
    public static function update(string $uuid, string $email, string $name): bool
    {
        $user = self::get($uuid);

        if (!$user)
        {
            return false;
        }

        $user->email = $email;
        $user->name  = $name;

        return $user->save();
    }

    /**
     * Update the user's password.
     *
     * @param string $uuid
     * @param string $current_password
     * @param string $new_password
     *
     * @return bool
     */
    public static function updatePassword(string $uuid, string $current_password, string $new_password): bool
    {
        $user = self::get($uuid);

        if (!isset($user) || !Hash::check($current_password, $user->password))
        {
            return false;
        }

        $user->password = Hash::make($new_password);

        return $user->save();
    }

    /**
     * Delete a user by UUID.
     *
     * @param string $uuid
     * @return bool
     */
    public static function delete(string $uuid): bool
    {
        $user = self::get($uuid);

        if (!$user)
        {
            return false;
        }

        return (bool) $user->delete();
    }

    /**
     * Restore a soft-deleted user.
     *
     * @param string $uuid
     *
     * @return bool
     */
    public static function restore(string $uuid): bool
    {
        return Model::withTrashed()
            ->where(Model::UUID, $uuid)
            ->restore() > 0;
    }
}
