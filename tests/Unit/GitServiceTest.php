<?php

namespace Tests\Unit;

use App\Services\GitService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class GitServiceTest extends TestCase
{
    private GitService $service;

    private string $testRepoPath;

    private const TEST_DIR = 'test';

    protected function setUp(): void
    {
        parent::setUp();

        // Create test repository path
        $this->testRepoPath = storage_path($this::TEST_DIR.'/test_git_'.uniqid());

    }

    protected function tearDown(): void
    {
        if (File::exists($this->testRepoPath)) {
            // Force delete the directory and all its contents
            File::deleteDirectory($this->testRepoPath, true);
        }

        parent::tearDown();
    }

    public function test_git_service()
    {
        $temporaryDirectory = $this->testRepoPath;
        $gitService = new GitService($temporaryDirectory);
        $gitService->clone('https://github.com/devnodesin/gitwiki-doc.git');

        $this->assertDirectoryExists($temporaryDirectory);
        $this->assertFileExists($temporaryDirectory.'/README.md');
        $this->assertFileExists($temporaryDirectory.'/LICENSE');

        //git pull
        $gitService->pull();

        $log = $gitService->log();
        $this->assertIsArray($log);
        foreach ($log as $entry) {
            $this->assertArrayHasKey('hash', $entry);
            $this->assertArrayHasKey('author', $entry);
            $this->assertArrayHasKey('date', $entry);
            $this->assertArrayHasKey('message', $entry);
        }

        File::deleteDirectory($this::TEST_DIR, true);

    }
}
