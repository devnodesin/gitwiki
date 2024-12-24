<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\GitService;
use Illuminate\Http\Request;

class GitController extends Controller
{
    private GitService $gitService;

    public function __construct(GitService $gitService)
    {
        $this->gitService = $gitService;
    }

    public function index()
    {
        $history = $this->gitService->log();
        $gitRemote = get_setting('git_remote');

        if (! $history) {
            $gitRemote = false;
        }

        return view('pages.git.index', [
            'title' => ['title' => 'Manage Content'],
            'gitRemote' => $gitRemote,
            'history' => $history,
            'changes' => ($gitRemote) ? $this->gitService->status() : false,
        ]);
    }

    /**
     * Pull latest changes from git repository
     */
    public function pull()
    {
        $result = $this->gitService->pull();
        $status = ($result) ? 'success' : 'error';

        return redirect()
            ->back()
            ->with($status, $result);
    }

    // push() function to push the changes to the git repository using git service
    public function push()
    {
        $result = $this->gitService->push();
        $status = ($result) ? 'success' : 'error';

        return redirect()
            ->back()
            ->with($status, $result);
    }

    // init() function to initialize the git repository  using git service
    public function init()
    {
        $result = $this->gitService->init();
        $status = ($result) ? 'success' : 'error';
        //on success set the git_remote setting to the url
        if ($result) {
            set_setting('git_remote', 'local');
        }

        return redirect()
            ->back()
            ->with($status, $result);
    }

    // status() function to get the status of the git repository using git service
    public function status()
    {
        $result = $this->gitService->status();
        $status = ($result) ? 'success' : 'error';

        return redirect()
            ->back()
            ->with($status, $result);
    }

    // clone() function to clone the git repository using git service
    public function clone(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url',
        ]);

        $url = $validated['url'];

        $result = $this->gitService->clone($url);
        $status = ($result) ? 'success' : 'error';

        //on success set the git_remote setting to the url
        if ($result) {
            set_setting('git_remote', $url);
        }

        return redirect()
            ->back()
            ->with($status, 'Git clone successful: '.$url.', goto home to view content');
    }

    // commit() function to commit the changes to the git repository using git service
    public function commit()
    {
        $result = $this->gitService->commit();
        $status = ($result) ? 'success' : 'error';

        return redirect()
            ->back()
            ->with($status, $result);
    }

    // reset() function to reset the changes to the git repository using git service
    public function reset(Request $request, $hash = null)
    {
        $hash = $hash ?? $request->query('hash');

        $result = $this->gitService->reset($hash);
        $status = ($result) ? 'success' : 'error';

        return redirect()
            ->back()
            ->with($status, $result);
    }
}
