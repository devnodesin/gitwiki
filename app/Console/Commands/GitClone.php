<?php

namespace App\Console\Commands;

use App\Services\GitService;
use Illuminate\Console\Command;
use RuntimeException;

class GitClone extends Command
{
    protected $signature = 'wiki:clone {url} {--branch=}';

    protected $description = 'Clone a git repository into the storage directory';

    public function handle(): int
    {
        $url = $this->argument('url');
        $branch = $this->option('branch');
        $repoPath = storage_path('git');

        $branchInfo = $branch ? " (branch: {$branch})" : ' (branch: main)';
        $this->info("Cloning repository from {$url}{$branchInfo}...");

        try {
            $gitService = new GitService($repoPath);
            $gitService->cloneRepository($url, $branch);

            $this->info('Repository cloned successfully.');

            return 0;
        } catch (RuntimeException $e) {
            $this->error($e->getMessage());

            return 1;
        }
    }
}
