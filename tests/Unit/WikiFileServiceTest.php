<?php

namespace Tests\Unit;

use App\Services\WikiFileService;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class WikiFileServiceTest extends TestCase
{
    private WikiFileService $service;

    private string $testStoragePath;

    private string $testImagesPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new WikiFileService;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_get_image_path_with_existing_file(): void
    {
        // Create test image file
        $testFile = '/test-image.png';

        // Test getImagePath
        $result = $this->service->getImagePath($testFile);

        // Assertions
        $this->assertNotNull($result);
    }

    public function test_get_image_path_with_not_existing_file(): void
    {
        // Create test image file
        $testFile = '/wiki/images/nofile'.uniqid().'.jpg';

        // Test getImagePath
        $result = $this->service->getImagePath($testFile);

        // Assertions
        $this->assertNull($result);
    }

    public function test_get_wiki_content_with_existing_file(): void
    {
        // Create test wiki file
        $testFile = '00-general/tasks-list';

        // Test getWikiContent
        $result = $this->service->getWikiContent($testFile);

        //dd($result);

        // Assertions
        $this->assertNotNull($result);
    }

    public function test_get_wiki_content_with_not_existing_file(): void
    {
        // Create test wiki file
        $testFile = '/00-general/task-list'.uniqid();

        // Test getWikiContent
        $result = $this->service->getWikiContent($testFile);

        // Assertions
        $this->assertNull($result);
    }

    public function test_get_directory_listing(): void
    {
        // Test getDirectoryListing
        $result = $this->service->getDirectoryListing();

        // Assertions
        $this->assertIsArray($result);
        $this->assertNotEmpty($result);

        foreach ($result as $directory) {
            $this->assertIsArray($directory);
            $this->assertNotEmpty($directory);
        }
    }
}
