@extends('layouts.default')
@section('content')
    <div class="row pt-4">
        <div class="col">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('info'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="d-flex justify-content-start mb-2">
                
                <a id="btnGitUpdate" class="btn btn-dark" href="{{ route('git.pull') }}">
                    <i class="bi bi-arrow-clockwise"></i> Git Update
                </a>
            </div>
            <hr>
        </div>
    </div>
    <div class="row d-none" id="loading">
        <div class="text-center py-2">
            <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="pt-2">
                <span class="text-secondary">Git Updating...</span>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h5 class="card-title mb-0">Git History</h5>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @foreach($history as $commit)
                <div class="list-group-item d-flex justify-content-between align-items-start">
                    <div>
                        <div><code>{{ $commit['hash'] }}</code> {{ $commit['message'] }}</div>
                        <small class="text-secondary fst-italic">by {{ $commit['author'] }} on {{ $commit['date'] }}</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-link btn-sm py-0 px-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Reset to this commit</a></li>
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@stop


@push('scriptsFooter')
    <script>
        document.getElementById('btnGitUpdate').addEventListener('click', (event) => {
            document.getElementById('btnGitUpdate').disabled = true;
            document.getElementById('loading').classList.remove('d-none');
        });
    </script>
@endpush