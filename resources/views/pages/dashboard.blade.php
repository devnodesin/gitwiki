@extends('layouts.default')
@section('content')

    <div class="row pt-4">

        <div class="col-12 col-md-6">
            <div class="card text-bg-success mb-3">
                <div class="card-header">
                    Links
                </div>
                <ul class="list-group list-group-flush">
                    @foreach ($links as $name => $url)
                        <li class="list-group-item">
                            <a class="link-dark link-offset-2" href="{{ url($url) }}">{{ $name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>
@stop
