<?php

namespace App\Console\Commands;

use App\Services\GitService;
use Illuminate\Console\Command;
use RuntimeException;

class GitPull extends Command
{
    protected $signature = 'gitwiki:pull';

    protected $description = 'Pull latest changes from the git repository';

    public function handle(): int
    {
        $repoPath = storage_path('git');

        if (! is_dir($repoPath)) {
            $this->error('Git repository not found. Please clone a repository first using gitwiki:clone.');

            return 1;
        }

        $this->info('Pulling latest changes...');

        try {
            $gitService = new GitService($repoPath);

            // Get current branch for informative message
            $currentBranch = $gitService->getCurrentBranch();
            $this->info("Current branch: {$currentBranch}");

            $gitService->pullRepository();

            // Show last commit info
            $lastCommit = $gitService->getLastCommit();
            $this->info('Successfully pulled changes.');
            $this->info("Latest commit: {$lastCommit['hash']} ({$lastCommit['date']->diffForHumans()})");
            $this->info("Commit message: {$lastCommit['message']}");

            return 0;
        } catch (RuntimeException $e) {
            $this->error($e->getMessage());

            return 1;
        }
    }
}
