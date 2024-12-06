<?php

namespace App\Support;

use Illuminate\Support\Str;

class WikiHelper
{
    /**
     * Converts a local file path to a URL for the wiki by
     * removing the local path and file extension.
     *
     * @param  string  $path  the local file path.
     * @return string the URL for the wiki.
     */
    public static function urlize(string $path): string
    {
        if ($path === null) {
            return '';
        }
        // for windows path separators
        $path = str_replace('\\', '/', $path);

        // Get the extension
        $pathInfo = pathinfo($path);
        $extension = $pathInfo['extension'] ?? '';

        $path = explode('git/pages', $path);
        $relativePath = str_replace($extension, '', $path[1]);

        $url = trim($relativePath, './');

        return $url;
    }

    /**
     * Converts a given path to a url that can be used in the wiki
     *
     * @param  string  $path  the path to convert
     * @return string the converted url
     */
    public static function title(?string $filename): string
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
}
