<?php

namespace App\Services;

use App\Support\SupportWikiHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use RuntimeException;

class WikiFileService
{
    private string $gitPath;

    private string $pagesPath;

    private string $imagesPath;

    public function __construct()
    {

        $this->gitPath = storage_path((string) 'git');
        $this->pagesPath = $this->gitPath.'/pages';
        $this->imagesPath = $this->gitPath.'/images';
    }

    /**
     * Get a list of directories and their files under the pages directory
     *
     * @return array<string, non-empty-array<int, array{title: string|null, url: string}>>
     *
     * @throws RuntimeException If base directory doesn't exist
     */
    public function getDirectoryListing(): array
    {
        if (! File::exists($this->pagesPath)) {
            throw new RuntimeException('Git pages directory does not exist');
        }

        $directories = File::directories($this->pagesPath);
        $listing = [];

        foreach ($directories as $directory) {
            $dirName = basename($directory);

            // Skip special directories
            if (in_array($dirName, ['.git', 'node_modules', 'vendor'])) {
                continue;
            }
            if (! preg_match('/^[a-z0-9-]+$/', $dirName)) {
                continue;
            }

            // Get all files in this directory recursively
            $files = Collection::make(File::allFiles($directory))
                ->filter(function ($file) {
                    // Filter out unwanted files
                    $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                    $extension = strtolower($file->getExtension());

                    if (str_ends_with($filename, '.ignore')) {
                        return false;
                    }

                    return preg_match('/^[a-z0-9.-]+$/', $filename) &&
                           in_array($extension, ['md', 'markdown']);
                })
                ->map(function (\Symfony\Component\Finder\SplFileInfo $file) {
                    return [
                        'title' => SupportWikiHelper::title($file->getFilename()),
                        'url' => SupportWikiHelper::urlize($file->getPathname()),
                    ];
                })
                ->values()
                ->all();

            if (! empty($files)) {
                $listing[SupportWikiHelper::title($dirName)] = $files;
            }
        }

        return $listing;
    }

    /**
     * Get the content of a wiki file from either root or directory structure
     *
     * @param  string  $slug  The wiki slug (e.g., "00-general/tasks-list" or "about" or "00-general/subdir/tasks-list")
     * @return string|null The file content or null if not found
     */
    public function getWikiContent(string $slug): ?string
    {
        // url to path
        $path = $this->pagesPath.'/'.trim($slug, '/').'.md';

        if (! File::exists($path)) {
            return null;
        }

        return File::get($path);
    }

    public function updateWikiContent(string $slug, string $content): bool
    {
        $path = $this->pagesPath.'/'.trim($slug, '/').'.md';

        if (! File::exists($path)) {
            return false;
        }

        // Ensure content is a string
        $content = (string) $content;

        return File::put($path, $content) !== false;
    }

    public function getImagePath(string $slug): ?string
    {
        $path = $this->imagesPath.'/'.trim($slug, '/');
        if (! File::exists($path)) {
            return null;
        }

        // Check if the file is actually an image
        $mime = mime_content_type($path);
        $allowedMimes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml',
        ];

        if (! in_array($mime, $allowedMimes)) {
            return null;
        }

        return $path;
    }
}
