<?php

namespace Tests\Feature;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
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
            // Create a test admin user
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

    public function test_admin_view_user_list()
    {
        $user = User::where('email', 'test@example.com')->first();
        $this->actingAs($user);

        $response = $this->get(route('user.list'));
        $response->assertViewIs('pages.user.list');
    }

    public function test_reader_view_user_list()
    {
        $user = User::where('email', 'test.reader@example.com')->first();
        $this->actingAs($user);

        $response = $this->get(route('user.list'));
        $response->assertRedirect(route('home'));
        $response->assertStatus(302);
    }

    public function test_nologin_view_user_list()
    {
        $response = $this->get(route('user.list'));
        $response->assertRedirect(route('login'));
        $response->assertStatus(302);
    }

    public function test_admin_update_user()
    {
        $user = User::where('email', 'test@example.com')->first();
        $this->actingAs($user);

        $user_id = User::where('email', 'test.reader@example.com')->first()->id;

        // Method 2: Using CSRF token
        $response = $this->from(route('user.list'))
            ->post(route('user.update', ['id' => $user_id]), [
                '_token' => csrf_token(),
                'name' => 'I am a Reader Updated',
                'email' => 'test.reader@example.com',
                'role' => 'reader',
                'password' => '',
            ]);

        $response->assertRedirect(route('user.list'));
        $response->assertSessionHas('success', 'User updated successfully');

        $this->assertDatabaseHas('users', [
            'id' => $user_id,
            'name' => 'I am a Reader Updated',
            'email' => 'test.reader@example.com',
            'role' => 'reader',
        ]);
    }

    public function test_admin_delete_user()
    {

        // Create a test reader user
        if (User::where('email', 'test.delete@example.com')->exists()) {
            $this->testAdminUser = User::where('email', 'test.delete@example.com')->first();
        } else {
            // Create a test admin user
            $this->testReaderUser = User::factory()->create([
                'name' => 'Delete Reader',
                'email' => 'test.delete@example.com',
                'password' => Hash::make('test123'),
                'role' => UserRoles::Reader->value,
            ]);
        }

        $user = User::where('email', 'test@example.com')->first();
        $this->actingAs($user);

        $user_id = User::where('email', 'test.delete@example.com')->first()->id;

        $response = $this->delete(route('user.delete', ['id' => $user_id]));

        $response->assertRedirect(route('user.list'));
        $response->assertSessionHas('success', 'User deleted successfully');

        $this->assertDatabaseMissing('users', [
            'id' => $user_id,
            'name' => 'Delete Reader',
            'email' => 'test.delete@example.com',
            'role' => UserRoles::Reader->value,
        ]);
    }

    public function test_viewer_delete_user()
    {
        // Create a test reader user
        if (User::where('email', 'test.delete.reader@example.com')->exists()) {
            $this->testAdminUser = User::where('email', 'test.delete.reader@example.com')->first();
        } else {
            // Create a test admin user
            $this->testReaderUser = User::factory()->create([
                'name' => 'Delete Reader',
                'email' => 'test.delete.reader@example.com',
                'password' => Hash::make('test123'),
                'role' => UserRoles::Reader->value,
            ]);
        }

        $user = User::where('email', 'test.reader@example.com')->first();
        $this->actingAs($user);

        $user_id = User::where('email', 'test.delete.reader@example.com')->first()->id;

        $response = $this->delete(route('user.delete', ['id' => $user_id]));

        $response->assertStatus(302);
    }
}
