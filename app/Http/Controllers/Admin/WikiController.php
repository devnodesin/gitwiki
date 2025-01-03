<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MarkdownService;
use App\Services\WikiFileService;
use App\Support\SupportWikiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class WikiController extends Controller
{
    private WikiFileService $wikiFileService;

    private MarkdownService $markdownService;

    public function __construct(
        WikiFileService $wikiFileService,
        MarkdownService $markdownService,
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
     * @param  string  $slug  The full path after /wiki/ (e.g., "00-general/tasks-list" or "about")
     */
    public function view(string $slug)
    {

        if (Str::endsWith($slug, '.lock') && auth()->guest()) {
            throw new HttpException(403, 'Login to view this page.');
        }

        $content = $this->wikiFileService->getWikiContent($slug);
        if ($content === null) {
            return response()->view('errors.404', [
                'title' => ['title' => 'Page Not Found'],
            ], 404);
        }

        $html = $this->markdownService->toHtml($content);
        $title = ['title' => SupportWikiHelper::title($slug)];

        return view('pages.wiki.view', compact('title', 'html'));
    }

    public function edit(string $slug)
    {

        if (Str::endsWith($slug, '.lock') && auth()->guest()) {
            throw new HttpException(403, 'Login to view this page.');
        }

        $content = $this->wikiFileService->getWikiContent($slug);
        if ($content === null) {
            return response()->view('errors.404', [
                'title' => ['title' => 'Page Not Found'],
            ], 404);
        }
        //dd($content);
        $title = ['title' => 'Edit: '.SupportWikiHelper::title($slug)];

        return view('pages.wiki.edit', compact('title', 'content'));
    }

    public function save(Request $request, string $slug)
    {
        // Validate the request
        $request->validate([
            'content' => 'required|string',
        ]);

        /** @var string */
        $content = $request->input('content');

        $this->wikiFileService->updateWikiContent($slug, $content);

        return redirect()->route('wiki.page', ['any' => $slug])
            ->with('success', 'Page saved successfully.');
    }

    /**
     * Serve wiki images
     *
     * @param  string  $slug  The image path after /wiki/image/
     */
    public function image(string $slug): BinaryFileResponse
    {
        $extension = pathinfo($slug, PATHINFO_EXTENSION);
        if (! in_array(strtolower($extension), ['png', 'jpg', 'jpeg', 'gif', 'webp', 'svg'])) {
            throw new HttpException(404, 'Image not found or invalid file type');
        }

        $path = $this->wikiFileService->getImagePath($slug);
        if ($path === null) {
            throw new HttpException(404, 'Image not found or invalid file type');
        }

        return response()->file($path);
    }
}
