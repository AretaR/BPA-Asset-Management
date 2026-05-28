<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileAvatarTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_upload_avatar_to_profile(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user);

        $avatar = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->put(route('users.updateProfile'), [
            'name' => 'New Name',
            'email' => 'newemail@example.com',
            'phone' => '12345678',
            'position' => 'Developer',
            'avatar' => $avatar,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('users.profile'));
        $response->assertSessionHas('success', 'Profile updated successfully.');

        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('newemail@example.com', $user->email);
        $this->assertNotNull($user->avatar);

        Storage::disk('public')->assertExists($user->avatar);
    }

    public function test_admin_can_create_user_with_avatar(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $avatar = UploadedFile::fake()->image('new_user_avatar.jpg');

        $response = $this->post(route('users.store'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'staff',
            'employee_id' => 'EMP123',
            'avatar' => $avatar,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'User created successfully.');

        $newUser = User::where('email', 'johndoe@example.com')->first();
        $this->assertNotNull($newUser);
        $this->assertNotNull($newUser->avatar);
        Storage::disk('public')->assertExists($newUser->avatar);
    }

    public function test_admin_can_update_user_avatar(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        $user = User::factory()->create([
            'avatar' => 'avatars/old_avatar.jpg',
        ]);
        Storage::disk('public')->put('avatars/old_avatar.jpg', 'fake content');

        $newAvatar = UploadedFile::fake()->image('new_avatar.jpg');

        $response = $this->put(route('users.update', $user), [
            'name' => 'Updated Name',
            'email' => $user->email,
            'role' => $user->role,
            'avatar' => $newAvatar,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('success', 'User updated successfully.');

        $user->refresh();
        $this->assertNotNull($user->avatar);
        $this->assertNotEquals('avatars/old_avatar.jpg', $user->avatar);
        Storage::disk('public')->assertExists($user->avatar);
        Storage::disk('public')->assertMissing('avatars/old_avatar.jpg');
    }
}
