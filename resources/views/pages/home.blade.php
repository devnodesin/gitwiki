@extends('layouts.default')
@section('content')
    <div class="text-center my-5 py-5">
        @if (Route::has('login'))
            <nav class="-mx-3 flex flex-1 justify-end">
                @auth
                    <div class="container-fluix py-5">
                        <h1>Bunisess Hub</h1>
                        <p>a 360 App</p>
                        <p><a href="{{ route('dashboard') }}" class="btn btn-warning btn-lg shadow-lg">Access
                                Application</a>
                        </p>
                    </div>
                @else
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="p-5 bg-white border rounded shadow">
                            <x-login />
                        </div>
                    </div>
                @endauth
            </nav>
        @endif
    </div>
@stop
