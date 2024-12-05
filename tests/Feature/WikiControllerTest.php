<?php

namespace Tests\Feature;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WikiControllerTest extends TestCase
{
    protected $testAdminUser;

    protected $testReaderUser;

    protected $gitService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test admin user
        if (User::where('email', 'test@example.com')->exists()) {
            $this->testAdminUser = User::where('email', 'test@example.com')->first();
        } else {
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

    public function test_view_home_as_guest()
    {
        $response = $this->get(route('home'));
        $this->assertGuest();
    }

    public function test_view_home_as_admin()
    {
        $user = User::where('email', 'test@example.com')->first();
        $this->actingAs($user);

        $response = $this->get(route('home'));
        $response->assertViewIs('pages.wiki.index');
    }

    public function test_view_home_as_reader()
    {
        $user = User::where('email', 'test.reader@example.com')->first();
        $this->actingAs($user);

        $response = $this->get(route('home'));
        $response->assertViewIs('pages.wiki.index');
    }

    public function test_view_wiki_page_as_reader()
    {
        $user = User::where('email', 'test.reader@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/wiki/99-test/test');
        $response->assertViewIs('pages.wiki.view');
    }

    public function test_view_wiki_image_as_reader()
    {
        $user = User::where('email', 'test.reader@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/wiki/images/test/test.jpg');
        $this->assertNotNull($response->getContent());
    }

    public function test_view_wiki_page_as_guest()
    {
        $response = $this->get('/wiki/99-test/test');
        $this->assertGuest();
    }

    public function test_view_wiki_image_as_guest()
    {
        $response = $this->get('/wiki/images/test/test.jpg');
        $this->assertGuest();
    }
}
