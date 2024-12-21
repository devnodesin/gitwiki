<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use RuntimeException;
use App\Services\GitService;

class GitController extends Controller
{

    private GitService $gitService;

    public function __construct(
        GitService $gitService
    ) {
        $this->gitService = $gitService;
    }

    public function index()
    {
        
        $history = $this->gitService->getHistory();

        return view('pages.git.index', [
            'title' => ['title' => 'Manage Git'],
            'history' => $history,
        ]);
    }

    /**
     * Pull latest changes from git repository
     */
    public function pull()
    {
        try {
            $result = $this->gitService->pull();;

            return redirect()
                ->back()
                ->with($result['status'], $result['message']);
        } catch (RuntimeException $e) {
            return redirect()
                ->route('home')
                ->with('error', 'Failed to update wiki content: ' . $e->getMessage());
        }
    }
}
