<?php

namespace App\Contracts\Services;

interface IUserService
{
    /**
     * Get authenticated user information.
     *
     * @param string $uuid
     *
     * @return array<string, mixed>
     */
    public static function me(string $uuid): array;

    /**
     * Authenticate user with email and password.
     *
     * @param string $email
     * @param string $password
     *
     * @return string|null
     */
    public static function login(string $email, string $password): ?string;

    /**
     * Create user
     *
     * @param string $email
     * @param string $name
     * @param string $password
     *
     * @return array<mixed>
     */
    public static function create(string $email, string $name, string $password): array;

    /**
     * Update user information.
     *
     * @param string $uuid
     * @param string $email
     * @param string $name
     *
     * @return void
     */
    public static function update(string $uuid, string $email, string $name): void;

    /**
     * Update user password.
     *
     * @param string $uuid
     * @param string $current_password
     * @param string $new_password
     *
     * @return void
     */
    public static function updatePassword(string $uuid, string $current_password, string $new_password): void;

    /**
     * Delete a user.
     *
     * @param string $uuid
     *
     * @return void
     */
    public static function delete(string $uuid): void;

    /**
     * Restore a deleted user.
     *
     * @param string $uuid
     *
     * @return void
     */
    public static function restore(string $uuid): void;
}
