<?php

namespace Tests\Unit\Helpers;

use App\Helpers\User as UserHelper;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_user_by_email(): void
    {
        $user = User::factory()->create([
            User::UUID     => \Illuminate\Support\Str::uuid()->toString(),
            User::NAME     => 'Test Only',
            User::EMAIL    => 'test@example.com',
            User::PASSWORD => Hash::make('Myt&st00'),
        ]);

        $foundUser = UserHelper::get('test@example.com');

        $this->assertNotNull($foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

    public function test_get_user_by_uuid(): void
    {
        $user = User::factory()->create();

        $foundUser = UserHelper::get($user->uuid);

        $this->assertNotNull($foundUser);
        $this->assertEquals($user->id, $foundUser->id);
    }

    public function test_create_user(): void
    {
        $email    = 'newuser@example.com';
        $name     = 'New User';
        $password = 'P@ssword123';

        $user = UserHelper::create($email, $name, $password);

        $this->assertDatabaseHas('users', [
            User::EMAIL => $email,
            User::NAME  => $name,
        ]);

        $this->assertTrue(Hash::check($password, $user->password));
        $this->assertNotNull($user->uuid);
        $this->assertDatabaseHas('users', [
            User::UUID => $user->uuid,
        ]);
    }

    public function test_update_user(): void
    {
        $user     = User::factory()->create();
        $newEmail = 'updated@example.com';
        $newName  = 'Updated User';

        $result = UserHelper::update($user->uuid, $newEmail, $newName);

        $this->assertTrue($result);
        $this->assertDatabaseHas('users', [
            User::EMAIL => $newEmail,
            User::NAME  => $newName,
        ]);
    }

    public function test_update_non_existing_user(): void
    {
        $result = UserHelper::update('non-existing-uuid', 'test@example.com', 'Test User');

        $this->assertFalse($result);
    }

    public function test_update_password(): void
    {
        $user = User::factory()->create([
            User::PASSWORD => Hash::make('oldpassword123'),
        ]);

        $result = UserHelper::updatePassword($user->uuid, 'oldpassword123', 'newpassword123');

        $this->assertTrue($result);
        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    public function test_update_password_with_incorrect_current_password(): void
    {
        $user = User::factory()->create([
            User::PASSWORD => Hash::make('password123'),
        ]);

        $result = UserHelper::updatePassword($user->uuid, 'wrongpassword', 'newpassword123');

        $this->assertFalse($result);
    }

    public function test_delete_user(): void
    {
        $user   = User::factory()->create();
        $result = UserHelper::delete($user->uuid);

        $this->assertTrue($result);
        $this->assertSoftDeleted('users', [
            'uuid' => $user->uuid,
        ]);
    }

    public function test_delete_non_existing_user(): void
    {
        $result = UserHelper::delete('non-existing-uuid');

        $this->assertFalse($result);
    }

    public function test_restore_user(): void
    {
        $user = User::factory()->create();
        $user->delete();

        $result = UserHelper::restore($user->uuid);

        $this->assertTrue($result);
        $this->assertDatabaseHas('users', [
            User::UUID       => $user->uuid,
            User::DELETED_AT => null,
        ]);
    }

    public function test_restore_non_existing_user(): void
    {
        $result = UserHelper::restore('non-existing-uuid');

        $this->assertFalse($result);
    }
}
