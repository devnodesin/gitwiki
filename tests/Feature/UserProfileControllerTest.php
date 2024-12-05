<?php

namespace Tests\Feature;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserProfileControllerTest extends TestCase
{
    protected $testAdminUser;

    protected $testReaderUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Check if the test user already exists
        if (User::where('email', 'test@example.com')->exists()) {
            $this->testAdminUser = User::where('email', 'test@example.com')->first();
        } else {
            // Create a test admin user
            $this->testAdminUser = User::factory()->create([
                'name' => 'Test',
                'email' => 'test@example.com',
                'password' => Hash::make('test123'),
                'role' => UserRoles::Admin->value,
            ]);
        }

        // Create a test reader user
        if (User::where('email', 'test.reader@example.com')->exists()) {
            $this->testReaderUser = User::where('email', 'test.reader@example.com')->first();
        } else {
            // Create a test reader user
            $this->testReaderUser = User::factory()->create([
                'name' => 'Test Reader',
                'email' => 'test.reader@example.com',
                'password' => Hash::make('test123'),
                'role' => UserRoles::Reader->value,
            ]);
        }
    }

    protected function tearDown(): void
    {
        // Clean up the test user
        if ($this->testAdminUser) {
            $this->testAdminUser->delete();
        }

        if ($this->testReaderUser) {
            $this->testReaderUser->delete();
        }

        parent::tearDown();
    }

    public function test_profile_route_without_login()
    {
        $response = $this->get(route('profile'));
        $response->assertRedirect(route('login'));
    }

    public function test_profile_route_admin_login()
    {
        $user = User::where('email', 'test@example.com')->first();
        $this->actingAs($user);
        $response = $this->get(route('profile'));
        $response->assertStatus(200);
    }

    public function test_profile_route_reader_login()
    {
        $user = User::where('email', 'test.reader@example.com')->first();
        $this->actingAs($user);
        $response = $this->get(route('profile'));
        $response->assertStatus(200);
    }

    public function test_update_user_profile_as_admin()
    {
        $user = User::where('email', 'test@example.com')->first();
        $this->actingAs($user);

        $response = $this->followingRedirects()
            ->from(route('profile'))
            ->post(route('profile.update', [
                '_token' => csrf_token(),
                'update_type' => 'profile',
                'name' => 'I am a Admin User',
                'email' => 'test@example.com',
            ]));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'I am a Admin User',
            'email' => 'test@example.com',
        ]);
    }

    public function test_update_user_password_as_admin()
    {
        $user = User::where('email', 'test@example.com')->first();
        $this->actingAs($user);

        $response = $this->followingRedirects()
            ->from(route('profile'))
            ->post(route('profile.update', [
                '_token' => csrf_token(),
                'update_type' => 'password',
                'password' => 'admin123',
                'password_confirmation' => 'admin123',
            ]));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
        $this->assertTrue(Hash::check('admin123', $user->fresh()->password));
    }

    public function test_update_user_profile_as_reader()
    {
        $user = User::where('email', 'test.reader@example.com')->first();
        $this->actingAs($user);

        $response = $this->followingRedirects()
            ->from(route('profile'))
            ->post(route('profile.update', [
                '_token' => csrf_token(),
                'update_type' => 'profile',
                'name' => 'I am a Reader User',
                'email' => 'test.reader@example.com',
            ]));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'I am a Reader User',
            'email' => 'test.reader@example.com',
        ]);
    }

    public function test_update_user_password_as_reader()
    {
        $user = User::where('email', 'test.reader@example.com')->first();
        $this->actingAs($user);

        $response = $this->followingRedirects()
            ->from(route('profile'))
            ->post(route('profile.update', [
                '_token' => csrf_token(),
                'update_type' => 'password',
                'password' => 'reader123',
                'password_confirmation' => 'reader123',
            ]));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
        $this->assertTrue(Hash::check('reader123', $user->fresh()->password));
    }
}
