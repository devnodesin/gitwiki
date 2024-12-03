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
        $gitService->cloneRepository('https://github.com/devnodesin/gitwiki-doc.git');

        $this->assertDirectoryExists($temporaryDirectory);
        $this->assertFileExists($temporaryDirectory.'/README.md');
        $this->assertFileExists($temporaryDirectory.'/LICENSE');

        //git pull
        $gitService->pullRepository();

        $log = $gitService->getLog();
        $this->assertIsArray($log);
        foreach ($log as $entry) {
            $this->assertArrayHasKey('hash', $entry);
            $this->assertArrayHasKey('author', $entry);
            $this->assertArrayHasKey('date', $entry);
            $this->assertArrayHasKey('message', $entry);
        }

        $currentBranch = $gitService->getCurrentBranch();
        $this->assertEquals('main', $currentBranch);

        $gitService->setCurrentBranch('main');
        $currentBranchAfterSwitch = $gitService->getCurrentBranch();
        $this->assertEquals('main', $currentBranchAfterSwitch);

        $lastCommit = $gitService->getLastCommit();
        $this->assertArrayHasKey('hash', $lastCommit);
        $this->assertArrayHasKey('date', $lastCommit);
        $this->assertArrayHasKey('message', $lastCommit);

        // test getLastCommitHash
        $lastCommitHash = $gitService->getLastCommitHash();
        $this->assertIsString($lastCommitHash);
        $this->assertNotEmpty($lastCommitHash);

        // test getLastCommitDate
        $lastCommitDate = $gitService->getLastCommitDate();
        $this->assertInstanceOf(Carbon::class, $lastCommitDate);

        File::deleteDirectory($this::TEST_DIR, true);

    }
}
