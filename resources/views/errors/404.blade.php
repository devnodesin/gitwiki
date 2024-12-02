@extends('layouts.404')

@section('content')
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="text-center">

            @if (!config('wiki.auth_enable'))
                @include('includes.pagenotfound')
            @else
                @auth
                    @include('includes.pagenotfound')
                @else
                    <p class="lead">
                        Please <a href="{{ route('login') }}" class="link-dark link-offset-2">Log In</a> to access this page.
                    </p>
                @endauth
            @endif
        </div>
    </div>
@stop

@push('stylesHead')
    <style>
        body {
            background: #f8f9fa;
        }
    </style>
@endpush
