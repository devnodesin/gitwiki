<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class GitService
{
    private string $repoPath;

    public function __construct(string $repoPath)
    {
        $this->repoPath = $repoPath;
    }

    private function __isGitRepository(): bool
    {
        //check if directory exists
        if (! File::exists($this->repoPath)) {
            return false;
        }

        //check if .git directory exists
        $process = Process::path($this->repoPath);
        $result = $process->run(['git', 'rev-parse', '--is-inside-work-tree']);

        return $result->successful();
    }

    /**
     * Executes a given Git command if the current directory is a Git repository.
     *
     * @param  array<int, string>  $command  The Git command to execute.
     * @return string|false The output of the command if successful, or false if the command fails or the directory is not a Git repository.
     */
    private function __runCommand(array $command): string|false
    {
        if (! $this->__isGitRepository()) {
            return false;
        }

        $result = Process::command($command)
            ->path($this->repoPath)
            ->run();

        if (! $result->successful()) {
            Log::info('Git command failed', [
                'command' => implode(' ', $command),
                'output' => $result->errorOutput(),
            ]);

            return false;
        }

        $output = trim($result->output());
        if (empty($output) && ! in_array('status', $command)) {
            $output = implode(' ', $command).' successful';
        }

        Log::info($output);

        return $output;
    }

    private function __initConfig()
    {
        $this->__runCommand(['git', 'config', 'user.name', 'GitWiki']);
        $this->__runCommand(['git', 'config', 'user.email', 'app@git.wiki']);
    }

    public function init(): string|false
    {
        if (! File::exists($this->repoPath)) {
            File::makeDirectory($this->repoPath, 0755, true);
        }

        if (! $this->__runCommand(['git', 'init'])) {
            return false;
        }

        // Create directories
        $directories = ['pages', 'images'];
        foreach ($directories as $dir) {
            $path = $this->repoPath.DIRECTORY_SEPARATOR.$dir;
            if (! File::exists($path)) {
                File::makeDirectory($path, 0755, true);
                File::put($path.DIRECTORY_SEPARATOR.'.gitkeep', '');
            }
        }

        // Add sample content
        $sampleDir = $this->repoPath.DIRECTORY_SEPARATOR.'pages'.DIRECTORY_SEPARATOR.'99-sample';
        if (! File::exists($sampleDir)) {
            File::makeDirectory($sampleDir, 0755, true);

            $content = <<<'MD'
            ## Gitwiki sample
            This is a sample cotent
            MD;

            File::put($sampleDir.DIRECTORY_SEPARATOR.'00-intro.md', $content);
        }

        // Check git config
        $this->__initConfig();

        // Add and commit files
        if (! $this->commit()) {
            return false;
        }

        return 'Git Initialized with sample content';
    }

    /**
     * Get git log entries
     *
     * @param  int  $limit  Number of log entries to retrieve
     * @return array<array{message: string, date: string, author: string, hash: string}>
     */
    public function log(int $limit = 10): array
    {
        $result = $this->__runCommand([
            'git',
            'log',
            '--pretty=format:%s|%cd|%an|%h',
            '--date=format:%d/%m/%Y, %I:%M:%S %p',
            "-{$limit}",
        ]);

        if (! $result) {
            return [];
        }

        $logs = [];
        $lines = explode("\n", trim($result));

        foreach ($lines as $line) {
            [$message, $date, $author, $hash] = explode('|', $line, 4);
            $logs[] = [
                'message' => $message,
                'date' => $date,
                'author' => $author,
                'hash' => $hash,
            ];
        }

        return $logs;
    }

    /**
     * Clone a git repository from the given URL.
     *
     * @param  string  $url  The URL of the git repository to clone.
     * @return string|false The result of the clone operation, or false on failure.
     */
    public function clone(string $url): string|false
    {
        // Check if URL is valid git repository
        $result = Process::command("git ls-remote {$url}")
            ->path(storage_path())
            ->run();

        if (! $result->successful()) {
            return false;
        }

        // Delete existing directory if exists
        if (File::exists($this->repoPath)) {
            File::deleteDirectory($this->repoPath);
        }

        // Create directory
        File::makeDirectory($this->repoPath, 0755, true);

        // Clone repository
        $result = Process::command("git clone {$url} .")
            ->path($this->repoPath)
            ->run();

        $this->__runCommand(['git', 'fetch']);

        // Check git config
        $this->__initConfig();

        return $result->successful() ? 'Git Clone successful' : false;
    }

    public function commit(): string|false
    {
        $result = $this->__runCommand(['git', 'add', '.']);
        if (! $result) {
            return 'Git add failed';
        }

        $result = $this->__runCommand(['git', 'commit', '-m', 'Updated on GitWiki']);
        if (! $result) {
            return 'Git commit failed: '.$result;
        }

        return $result;
    }

    public function status(): string|false
    {
        return $this->__runCommand(['git', 'status', '--porcelain']);
    }

    public function checkout(?string $branch = null): string|false
    {
        return ($branch === null) ? false : $this->__runCommand(['git', 'checkout', $branch]);
    }

    public function branch(): string|false
    {
        return $this->__runCommand(['git', 'rev-parse', '--abbrev-ref', 'HEAD']);
    }

    public function pull(): string|false
    {
        return $this->__runCommand(['git', 'pull']);
    }

    public function push(): string|false
    {
        return $this->__runCommand(['git', 'push']);
    }

    public function reset(?string $hash = null): string|false
    {
        $cmd = ['git', 'reset', '--hard'];
        if ($hash) {
            $cmd[] = $hash;
        }
        $output = $this->__runCommand($cmd);
        if ($output) {
            return 'Reset successful: '.$output;
        }

        return $output;
    }
}
