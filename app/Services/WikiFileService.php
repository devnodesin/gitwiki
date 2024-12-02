<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class WikiFileService
{
    private string $gitPath;

    private string $pagesPath;

    private string $imagesPath;

    public function __construct()
    {
        /** @var string */
        $gitPath = config('wiki.git_path');
        /** @var string */
        $pagesPath = config('wiki.pages_path');
        /** @var string */
        $imagesPath = config('wiki.images_path');

        $this->gitPath = storage_path((string) $gitPath);
        $this->pagesPath = $this->gitPath.$pagesPath;
        $this->imagesPath = $this->gitPath.$imagesPath;
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

            // Get all files in this directory recursively
            $files = Collection::make(File::allFiles($directory))
                ->filter(function ($file) {
                    // Filter out unwanted files
                    $extension = strtolower($file->getExtension());

                    return in_array($extension, ['md', 'markdown']);
                })
                ->map(function (\Symfony\Component\Finder\SplFileInfo $file) use ($dirName) {
                    // Get relative path from the base directory
                    $relativePath = $file->getRelativePathname();

                    return [
                        'title' => self::toTitle($file->getFilename()),
                        'url' => $dirName.'/'.self::toUrl($relativePath),
                    ];
                })
                ->values()
                ->all();

            if (! empty($files)) {
                $listing[self::toTitle($dirName)] = $files;
            }
        }

        return $listing;
    }

    /**
     * Format a filename into a readable title
     * Handles both path-based filenames and simple filenames
     *
     * @param  string|null  $filename  The filename to format
     * @return string The formatted title in Title Case
     */
    public static function toTitle(?string $filename): string
    {
        if ($filename === null) {
            return '';
        }

        $basename = pathinfo($filename, PATHINFO_FILENAME);
        /** @var string */
        $title = (string) preg_replace('/^\d+\-/', '', $basename);
        $title = str_replace(['_', '-'], ' ', $title);

        return Str::title($title);
    }

    /**
     * Format a file path into a URL-friendly string
     *
     * @param  string  $filename  The filename (e.g., "tasks-list.md" or "subdir/tasks-list.md")
     * @return string The URL-friendly path (e.g., "00-general/tasks-list" or "00-general/subdir/tasks-list")
     */
    private static function toUrl(string $filename): string
    {
        // Get the full path without extension
        $pathInfo = pathinfo($filename);
        $dirname = $pathInfo['dirname'] ?? '.';
        $extension = $pathInfo['extension'] ?? '';

        $relativePath = str_replace($extension, '', $dirname.'/'.$pathInfo['filename']);
        $url = trim($relativePath, './');

        return $url;
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
