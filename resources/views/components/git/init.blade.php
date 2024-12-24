<div class="row pt-4">
    <div class="card m-0 p-0">
        <div class="card-body p-0">
            <div class="p-3 text-center text-muted">
                <i class="bi bi-git h4"></i>
                <p class="mb-0">Git repository not initialized</p>
                <div class="mt-3">
                    <div class="mb-3">
                        <a id="btnGitInit" class="btn btn-sm btn-dark" href="{{ route('git.init') }}" wiki-loading>
                            <i class="bi bi-git"></i> Git Init (Local Only)
                        </a>
                    </div>
                    <div class="d-flex align-items-center justify-content-center my-3">
                        <hr class="flex-grow-1">
                        <span class="px-3 text-muted">OR</span>
                        <hr class="flex-grow-1">
                    </div>
                    <form action="{{ route('git.clone') }}" method="POST" class="mb-3">
                        @csrf
                        <div class="input-group input-group-sm justify-content-center">
                            <input type="url" name="url" class="form-control form-control-sm w-50"
                                placeholder="Enter Git Repository URL" required>
                            <button type="submit" class="btn btn-dark" wiki-loading>
                                <i class="bi bi-git"></i> Git Clone
                            </button>
                        </div>
                    </form>
                    <div class="mb-3"></div>
                    <p class="text-muted">Try repo: https://github.com/devnodesin/gitwiki-doc.git</p>
                </div>

            </div>
        </div>
    </div>
</div>
