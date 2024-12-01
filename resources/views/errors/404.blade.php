@extends('layouts.404')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="text-center">
        
        @auth
            <h1 class="display-1 fw-bold">404</h1>
            <p class="fs-3">Page not found.</p>
            <p class="lead">
                The page you're looking for doesn't exist.
            </p>
            <a href="{{ route('home') }}" class="btn btn-dark">Go Home</a>
        @else
            <p class="lead">
                Please <a href="{{ route('login') }}" class="link-dark link-offset-2">Log In</a> to access this page.
            </p>
            
        @endauth
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
