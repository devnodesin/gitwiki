<?php

namespace App\Console\Commands;

use App\Services\GitService;
use Illuminate\Console\Command;

class GitClone extends Command
{
    protected $signature = 'wiki:clone {url}';

    protected $description = 'Clone a git repository into the storage directory';

    public function handle(GitService $gitService)
    {
        $url = $this->argument('url');

        $result = $gitService->clone($url);

        if ($result === false) {
            $this->error('Failed to clone repository');

            return Command::FAILURE;
        }

        $this->info($result);

        return Command::SUCCESS;
    }
}
