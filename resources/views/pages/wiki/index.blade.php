@extends('layouts.default')
@section('content')

    <div class="row pt-3">
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
        </div>
    </div>


    <div class="row pt-2">
        @if (empty($dirs))
            <div class="alert alert-danger">
                Let's Get Started! Your Content Repository Needs Setup
            </div>
        @else
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
        @endif
    </div>
@stop
