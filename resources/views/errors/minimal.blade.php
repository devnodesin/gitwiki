@extends('layouts.default')
@section('content')
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="text-center">
            <h1 class="display-5 fw-bold">@yield('code') - @yield('title')</h1>
            <p class="lead">
                @yield('message')
            </p>
            <a href="{{ route('home') }}" class="btn btn-dark">Go Home</a>
        </div>
    </div>
@stop
