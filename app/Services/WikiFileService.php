<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class WikiFileService
{
    private string $basePath;

    public function __construct()
    {
        $this->basePath = storage_path('git/pages');
    }

    /**
     * Get a list of directories and their files under /storage/git/pages
     *
     * @return array<string, array<array{title: string, url: string}>>
     *
     * @throws RuntimeException If base directory doesn't exist
     */
    public function getDirectoryListing(): array
    {
        if (! File::exists($this->basePath)) {
            throw new RuntimeException('Git pages directory does not exist');
        }

        $directories = File::directories($this->basePath);
        $listing = [];

        foreach ($directories as $directory) {
            $dirName = basename($directory);

            // Skip special directories
            if (in_array($dirName, ['.git', 'node_modules', 'vendor'])) {
                continue;
            }

            // Get all files in this directory recursively
            $files = Collection::make(File::allFiles($directory))
                ->filter(function ($file) {
                    // Filter out unwanted files
                    $extension = strtolower($file->getExtension());

                    return in_array($extension, ['md', 'markdown']);
                })
                ->map(function (\Symfony\Component\Finder\SplFileInfo $file) use ($dirName) {
                    $filename = $file->getFilename();
                    assert(is_string($filename));

                    return [
                        'title' => $this->formatTitle($filename),
                        'url' => $this->formatUrl($dirName, $filename),
                    ];
                })
                ->values()
                ->all();

            if (! empty($files)) {
                // Remove numeric prefix and format directory name
                $formattedDirName = preg_replace('/^\d+\-/', '', $dirName);
                $formattedDirName = $this->formatTitle($formattedDirName);
                $listing[$formattedDirName] = $files;
            }
        }

        return $listing;
    }

    /**
     * Get the page title from the path
     *
     * @param  string  $path  The wiki path
     * @return string The formatted page title
     */
    public function getPageTitle(string $path): string
    {
        $parts = explode('/', trim($path, '/'));
        $filename = end($parts);

        if (! is_string($filename)) {
            throw new RuntimeException('Invalid path format');
        }

        // Remove .md extension if present
        $filename = preg_replace('/\.md$/', '', $filename);

        return $this->formatTitle($filename);
    }

    /**
     * Get the absolute path to a file in the git storage
     *
     * @param  string  $directory  Directory name
     * @param  string  $file  File path relative to directory
     * @return string Absolute path to the file
     *
     * @throws RuntimeException If file doesn't exist
     */
    public function getFilePath(string $directory, string $file): string
    {
        $path = $this->basePath.'/'.$directory.'/'.$file;

        if (! File::exists($path)) {
            throw new RuntimeException('File does not exist: '.$file);
        }

        return $path;
    }

    /**
     * Check if a file exists in a directory
     *
     * @param  string  $directory  Directory name
     * @param  string  $file  File path relative to directory
     */
    public function fileExists(string $directory, string $file): bool
    {
        return File::exists($this->basePath.'/'.$directory.'/'.$file);
    }

    /**
     * Format a filename into a readable title
     * Handles both path-based filenames and simple filenames
     *
     * @param  string|null  $filename  The filename to format
     * @return string The formatted title in Title Case
     */
    public function formatTitle(?string $filename): string
    {
        if ($filename === null) {
            return '';
        }

        // Remove any path info and extension
        $basename = pathinfo($filename, PATHINFO_FILENAME);

        // Replace dashes and underscores with spaces
        $title = str_replace(['-', '_'], ' ', $basename);

        // Convert to title case
        return Str::title($title);
    }

    /**
     * Format a file path into a URL-friendly string
     *
     * @param  string  $directory  The directory name (e.g., "00-general")
     * @param  string  $filename  The filename (e.g., "tasks-list.md")
     * @return string The URL-friendly path (e.g., "00-general/tasks-list")
     */
    private function formatUrl(string $directory, string $filename): string
    {
        // Remove file extension
        $basename = pathinfo($filename, PATHINFO_FILENAME);

        // Combine directory and file
        return $directory.'/'.$basename;
    }

    /**
     * Get the content of a wiki file
     *
     * @param  string  $directory  Directory name
     * @param  string  $file  File path relative to directory
     * @return string|null File content or null if file doesn't exist
     */
    public function getFileContent(string $directory, string $file): ?string
    {
        $path = $this->basePath.'/'.$directory.'/'.$file;

        if (! File::exists($path)) {
            return null;
        }

        return File::get($path);
    }

    /**
     * Get the content of a wiki file from either root or directory structure
     *
     * @param  string  $path  The wiki path (e.g., "00-general/tasks-list" or "about")
     * @return string|null The file content or null if not found
     */
    public function getWikiContent(string $path): ?string
    {
        // Try root directory first
        $rootFile = storage_path('git/'.trim($path, '/').'.md');
        if (file_exists($rootFile)) {
            $content = file_get_contents($rootFile);

            return $content === false ? null : $content;
        }

        // Try directory structure
        $parts = explode('/', trim($path, '/'));
        if (count($parts) !== 2) {
            return null;
        }

        [$directory, $file] = $parts;
        $filename = $file.'.md';

        if (! $this->fileExists($directory, $filename)) {
            return null;
        }

        return $this->getFileContent($directory, $filename);
    }
}
