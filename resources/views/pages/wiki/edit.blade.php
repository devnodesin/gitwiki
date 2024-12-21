@extends('layouts.default')
@section('content')

    <div class="row">
        <div class="col">
            <form method="POST" action="{{ route('wiki.save', ['any' => Str::beforeLast(Str::after(request()->path(), 'wiki/'), '/edit')]) }}">
                @csrf
                <textarea id="editor" name="content">
                    {!! $content !!}
                </textarea>
                <div class="row border-top border-3 py-2 mt-2">
                    <div class="col">
                        <a class="btn btn-sm btn-outline-dark" href="{{ route('wiki.page', ['any' => Str::beforeLast(Str::after(request()->path(), 'wiki/'), '/edit')]) }}">
                            <i class="bi bi-caret-left-fill"></i> Back
                        </a>
                        <button type="submit" class="btn btn-sm btn-outline-dark">
                            <i class="bi bi-pencil-fill"></i> Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('stylesHead')
        
    @endpush

    @push('scriptsFooter')
        @vite(['resources/js/editor.js'])       
    @endpush

@stop
