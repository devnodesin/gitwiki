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

    /**
     * Run a git command and return its output
     * this method will be used to run simple git commands
     * for complex commands, we use Process
     *
     * @param  array<string>  $command  Command array where each element is a command part
     * @return string Command output
     *
     * @throws RuntimeException If the command fails
     */
    private function runCommand(array $command): string
    {
        $result = Process::command($command)
            ->path($this->repoPath)
            ->run();

        if (! $result->successful()) {
            throw new RuntimeException('Git command failed: '.$result->errorOutput());
        }

        return trim($result->output());
    }

    /**
     * Get the hash of the last commit
     *
     * @return string The short hash of the last commit
     *
     * @throws RuntimeException If git command fails
     */
    public function getLastCommitHash(): string
    {
        return $this->runCommand(['git', 'rev-parse', '--short', 'HEAD']);
    }

    /**
     * Get the date of the last commit
     *
     * @return Carbon The date of the last commit
     *
     * @throws RuntimeException If git command fails
     */
    public function getLastCommitDate(): Carbon
    {
        $timestamp = $this->runCommand(['git', 'log', '-1', '--format=%ct']);

        return Carbon::createFromTimestamp($timestamp);
    }

    /**
     * Pull latest changes from the current branch
     *
     * @return array{status: string, message: string} Pull status and message
     *
     * @throws RuntimeException If git pull fails
     */
    public function pull(): array
    {
        $output = $this->runCommand(['git', 'pull']);
        $hash = $this->getLastCommitHash();

        if (str_contains($output, 'Already up to date')) {
            return [
                'status' => 'info',
                'message' => "Already up to date #{$hash}",
            ];
        }

        // Check if files were updated
        if (str_contains($output, 'Updating') || str_contains($output, 'files changed')) {
            return [
                'status' => 'success',
                'message' => "Successfully updated to #{$hash}",
            ];
        }

        return [
            'status' => 'error',
            'message' => $output,
        ];
    }
}
