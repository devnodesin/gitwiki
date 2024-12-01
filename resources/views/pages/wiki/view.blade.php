@extends('layouts.default')
@section('content')

    <div class="row">
        <div class="col">
            {!! $html !!}
        </div>
    </div>
    <div class="row border-top border-3 py-2 mt-2">
        <div class="col">
            <a class="btn btn-dark" href="{{ route('wiki') }}">
                <i class="bi bi-caret-left-fill"></i> Back
            </a>
        </div>
    </div>


    @push('stylesHead')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.10.0/styles/a11y-dark.min.css"
            integrity="sha512-Vj6gPCk8EZlqnoveEyuGyYaWZ1+jyjMPg8g4shwyyNlRQl6d3L9At02ZHQr5K6s5duZl/+YKMnM3/8pDhoUphg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
    @endpush

    @push('scriptsFooter')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/mermaid/11.4.0/mermaid.min.js"
            integrity="sha512-qglQ1LSNAz8XKHfeL5cl9/Z7FnG4v9PodzbvytCLA6qJcB5axH9oovvSNlFwMj46jdHHsOQGkEiUGSf3Lrr8Gg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
        <script>
            // first, find all the div.code blocks
            document.addEventListener('DOMContentLoaded', (event) => {
                document.querySelectorAll('code.code').forEach((el) => {
                    hljs.highlightElement(el);
                });
            });
        </script>
    @endpush

@stop
