<div class="row pt-4">
    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Changed Files</h5>
            <div>
                <a href="{{ route('git.reset') }}" class="btn btn-sm btn-danger me-2" wiki-loading>
                    <i class="bi bi-x-circle"></i> Discard
                </a>
                <a href="{{ route('git.commit') }}" class="btn btn-sm btn-dark" wiki-loading>
                    <i class="bi bi-check2-circle"></i> Save
                </a>
            </div>
        </div>
        <div class="card-body">
            <p><code>{{ $changes }}</code></p>
        </div>
    </div>
</div>