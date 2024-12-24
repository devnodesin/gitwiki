<div class="row pt-4">
    <div class="card mt-3 p-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Git History <span class="fs-6 text-secondary fst-italic">(last 10 commits)</spanc>
            </h5>
            @if (!empty($gitRemote) && $gitRemote !== 'local')
                <div class="dropdown">
                    <button class="btn btn-sm btn-light {{ !empty($changes) ? 'disabled' : '' }}" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li>
                            <a class="dropdown-item" href="{{ route('git.pull') }}" wiki-loading>
                                <i class="bi bi-cloud-download-fill"></i> Git Pull
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('git.push') }}" wiki-loading>
                                <i class="bi bi-cloud-upload-fill"></i> Git Push
                            </a>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @foreach ($history as $commit)
                    <div class="list-group-item d-flex justify-content-between align-items-start">
                        <div>
                            <div><code>{{ $commit['hash'] }}</code> {{ $commit['message'] }}</div>
                            <small class="text-secondary fst-italic">by {{ $commit['author'] }} on
                                {{ $commit['date'] }}</small>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-link btn-sm py-0 px-2" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ route('git.reset.hash', ['hash' => $commit['hash']]) }}" wiki-loading>
                                        Reset to {{$commit['hash']}}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
