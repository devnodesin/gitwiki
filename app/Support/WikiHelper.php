<?php

namespace App\Support;

use Illuminate\Support\Str;

class WikiHelper
{
    /**
     * Converts a local file path to a URL for the wiki by
     * removing the local path and file extension.
     *
     * @param  string  $filename  the local file path.
     * @return string the URL for the wiki.
     */
    public static function urlize(?string $filename): string
    {
        if ($filename === null) {
            return '';
        }

        // for windows path separators
        $path = str_replace('\\', '/', $filename);

        // Get the extension
        $pathInfo = pathinfo($path);
        $extension = $pathInfo['extension'] ?? '';

        $path = explode('git/pages', $path);
        $relativePath = str_replace($extension, '', $path[1]);

        $url = trim($relativePath, './');

        return $url;
    }

    /**
     * Formats a filename into a readable title by removing numbers
     * and replacing special characters.
     *
     * @param  string|null  $filename  The filename to format
     * @return string The formatted title in Title Case
     */
    public static function title(?string $filename): string
    {
        if ($filename === null) {
            return '';
        }

        $basename = pathinfo($filename, PATHINFO_FILENAME);

        if (Str::endsWith($basename, '.lock')) {
            $basename = pathinfo($filename, PATHINFO_FILENAME);
            $basename = (string) preg_replace('/\.lock$/', ' 🔒', $basename);
        }

        /** @var string */
        $title = (string) preg_replace('/^\d+\-/', '', $basename);
        $title = str_replace(['_', '-'], ' ', $title);

        return Str::title($title);
    }
}
