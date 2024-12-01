<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Process;
use RuntimeException;

class GitService
{
    private string $repoPath;

    public function __construct(string $repoPath)
    {
        $this->repoPath = $repoPath;
    }

    /**
     * Clone a git repository
     *
     * @param  string  $url  Repository URL to clone
     * @param  string|null  $branch  Branch to clone (optional)
     *
     * @throws RuntimeException If clone fails
     */
    public function cloneRepository(string $url, ?string $branch = null): void
    {
        $command = ['git', 'clone'];
        if ($branch) {
            $command[] = '-b';
            $command[] = $branch;
        } else {
            $command[] = '-b';
            $command[] = 'main';
        }
        $command[] = $url;
        $command[] = $this->repoPath;

        $result = Process::run($command);

        if (! $result->successful()) {
            throw new RuntimeException('Failed to clone repository: '.$result->errorOutput());
        }
    }

    /**
     * Pull latest changes from remote repository
     *
     * @throws RuntimeException If pull fails
     */
    public function pullRepository(): void
    {
        $process = Process::path($this->repoPath);
        $result = $process->run(['git', 'pull']);

        if (! $result->successful()) {
            throw new RuntimeException('Failed to pull repository: '.$result->errorOutput());
        }
    }

    /**
     * Get git log entries
     *
     * @param  int  $limit  Number of log entries to retrieve
     * @return array<array{hash: string, author: string, date: string, message: string}>
     *
     * @throws RuntimeException If getting log fails
     */
    public function getLog(int $limit = 10): array
    {
        $process = Process::path($this->repoPath);
        $result = $process->run(
            ['git', 'log', '--pretty=format:%H|%an|%ad|%s', '--date=iso', "-{$limit}"]
        );

        if (! $result->successful()) {
            throw new RuntimeException('Failed to get git log: '.$result->errorOutput());
        }

        $logs = [];
        $lines = explode("\n", trim($result->output()));

        foreach ($lines as $line) {
            [$hash, $author, $date, $message] = explode('|', $line, 4);
            $logs[] = [
                'hash' => $hash,
                'author' => $author,
                'date' => $date,
                'message' => $message,
            ];
        }

        return $logs;
    }

    /**
     * Get current branch name
     *
     * @return string Current branch name
     *
     * @throws RuntimeException If getting branch fails
     */
    public function getCurrentBranch(): string
    {
        $process = Process::path($this->repoPath);
        $result = $process->run(['git', 'rev-parse', '--abbrev-ref', 'HEAD']);

        if (! $result->successful()) {
            throw new RuntimeException('Failed to get current branch: '.$result->errorOutput());
        }

        return trim($result->output());
    }

    /**
     * Switch to a different branch
     *
     * @param  string  $branch  Branch name to switch to
     *
     * @throws RuntimeException If switching branch fails
     */
    public function setCurrentBranch(string $branch): void
    {
        $process = Process::path($this->repoPath);
        $result = $process->run(['git', 'checkout', $branch]);

        if (! $result->successful()) {
            throw new RuntimeException('Failed to switch branch: '.$result->errorOutput());
        }
    }

    /**
     * Get last commit information
     *
     * @return array{hash: string, date: Carbon, message: string}
     *
     * @throws RuntimeException If getting commit info fails
     */
    public function getLastCommit(): array
    {
        $process = Process::path($this->repoPath);
        $result = $process->run(
            ['git', 'log', '-1', '--pretty=format:%H|%ad|%s', '--date=iso']
        );

        if (! $result->successful()) {
            throw new RuntimeException('Failed to get last commit: '.$result->errorOutput());
        }

        [$hash, $date, $message] = explode('|', trim($result->output()), 3);

        return [
            'hash' => $hash,
            'date' => Carbon::parse($date),
            'message' => $message,
        ];
    }
}
