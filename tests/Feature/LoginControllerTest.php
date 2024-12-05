<?php

namespace Tests\Feature;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
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

    private function loginUser($email, $password)
    {
        $response = $this->post('/login', [
            'email' => $email,
            'password' => $password,
        ]);

        return $response;
    }

    public function test_login_admin_user()
    {

        $response = $this->loginUser('test@example.com', 'test123');
        $this->assertAuthenticated();
        $response->assertRedirect(route('home'));
    }

    public function test_login_reader_user()
    {

        $response = $this->loginUser('test.reader@example.com', 'test123');
        $this->assertAuthenticated();
        $response->assertRedirect(route('home'));
    }

    public function test_login_wrong_email_user()
    {

        $response = $this->loginUser('wrongemail@example.com', 'test123');
        $this->assertGuest();
    }

    public function test_login_wrong_password_user()
    {

        $response = $this->loginUser('test@example.com', 'wrong_password');
        $this->assertGuest();
    }
}
