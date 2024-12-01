@extends('layouts.default')
@section('content')

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
                                <a class="link-dark link-offset-2"
                                    href="{{ route('wiki.page', ['any' => $file['url']]) }}">{{ $file['title'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
@stop
