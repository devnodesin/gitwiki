<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GitService;
use App\Services\MarkdownService;
use App\Services\WikiFileService;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class WikiController extends Controller
{
    private WikiFileService $wikiFileService;

    private MarkdownService $markdownService;

    private GitService $gitService;

    public function __construct(
        WikiFileService $wikiFileService,
        MarkdownService $markdownService,
        GitService $gitService
    ) {
        $this->wikiFileService = $wikiFileService;
        $this->markdownService = $markdownService;
        $this->gitService = $gitService;
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
            $lastCommit = [
                'hash' => $this->gitService->getLastCommitHash(),
                'date' => $this->gitService->getLastCommitDate(),
            ];
        } catch (RuntimeException $e) {
            $list = [];
            $lastCommit = [
                'hash' => 'unknown',
                'date' => now(),
            ];
        }

        return view('pages.wiki.index', [
            'title' => $title,
            'dirs' => $list,
            'lastCommit' => $lastCommit,
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
            return response()->view('errors.404', [
                'title' => ['title' => 'Page Not Found'],
            ], 404);
        }

        $html = $this->markdownService->toHtml($content);
        $title = ['title' => WikiFileService::toTitle($any)];

        return view('pages.wiki.view', compact('title', 'html'));
    }

    /**
     * Serve wiki images
     *
     * @param  string  $any  The image path after /wiki/image/
     */
    public function image(string $any): BinaryFileResponse
    {
        $path = $this->wikiFileService->getImagePath($any);
        if ($path === null) {
            abort(404, 'Image not found or invalid file type');
        }

        return response()->file($path);
    }

    /**
     * Pull latest changes from git repository
     */
    public function pull()
    {
        try {
            $result = $this->gitService->pull();

            return redirect()
                ->route('home')
                ->with($result['status'], $result['message']);
        } catch (RuntimeException $e) {
            return redirect()
                ->route('home')
                ->with('error', 'Failed to update wiki content: '.$e->getMessage());
        }
    }
}
