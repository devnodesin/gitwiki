@extends('layouts.default')
@section('content')

    <div class="row">
        <div class="col">
            {!! $html !!}
        </div>
    </div>
    <div class="row border-top border-3 py-2 mt-2">
        <div class="col">
            <a class="btn btn-sm btn-outline-dark" href="{{ route('home') }}">
                <i class="bi bi-caret-left-fill"></i> Back
            </a>
            <a class="btn btn-sm btn-outline-dark" href="{{ route('wiki.edit', ['any' => Str::after(request()->path(), 'wiki/')]) }}">
                <i class="bi bi-pencil-fill"></i> Edit
            </a>
        </div>
    </div>


    @push('stylesHead')
        @vite(['resources/css/wiki.css'])
    @endpush

    @push('scriptsFooter')

        @vite(['resources/js/wiki.js'])
        
    @endpush

@stop
