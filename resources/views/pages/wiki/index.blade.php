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
            @if (Auth::check() && Auth::user()->role === \App\Enums\UserRoles::Admin)
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-secondary">{{ $lastCommit['hash'] }} .
                        {{ $lastCommit['date']->diffForHumans() }}</span>
                    <a id="btnGitUpdate" class="btn btn-dark" href="{{ route('gitwiki.pull') }}">
                        <i class="bi bi-arrow-clockwise"></i> Git Update
                    </a>
                </div>
                <hr>
            @endif
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
    <div class="row pt-4">
        @foreach ($dirs as $dir => $files)
            <div class="col-12 col-md-6">
                <div class="card text-bg-dark mb-3 shadow">
                    <div class="card-header">
                        {{ $dir }}
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach ($files as $file)
                            <li class="list-group-item">
                                <a class="link-dark link-offset-3"
                                    href="{{ route('wiki.page', ['any' => $file['url']]) }}">{{ $file['title'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
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
