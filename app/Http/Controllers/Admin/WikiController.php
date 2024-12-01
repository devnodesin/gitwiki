<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MarkdownService;
use App\Services\WikiFileService;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class WikiController extends Controller
{
    private WikiFileService $wikiFileService;

    private MarkdownService $markdownService;

    public function __construct(
        WikiFileService $wikiFileService,
        MarkdownService $markdownService
    ) {
        $this->wikiFileService = $wikiFileService;
        $this->markdownService = $markdownService;
    }

    /**
     * Display wiki index page
     */
    public function index()
    {
        $title = [
            'title' => 'Wiki',
        ];

        try {
            $list = $this->wikiFileService->getDirectoryListing();
        } catch (RuntimeException $e) {
            $list = [];
        }

        return view('pages.wiki.index', [
            'title' => $title,
            'dirs' => $list,
        ]);
    }

    /**
     * Display wiki page
     *
     * @param  string  $any  The full path after /wiki/ (e.g., "00-general/tasks-list" or "about")
     */
    public function view(string $any)
    {
        $content = $this->wikiFileService->getWikiContent($any);
        if ($content === null) {
            abort(404);
        }

        $html = $this->markdownService->toHtml($content);
        $title = ['title' => $this->wikiFileService->getPageTitle($any)];

        return view('pages.wiki.view', compact('title', 'html'));
    }

    /**
     * Serve wiki images
     *
     * @param  string  $any  The image path after /wiki/image/
     */
    public function image(string $any): BinaryFileResponse
    {
        $imagePath = storage_path('git/images/'.trim($any, '/'));

        if (! file_exists($imagePath)) {
            Log::error('Wiki image not found: '.$imagePath);
            abort(404);
        }

        return response()->file($imagePath);
    }
}
