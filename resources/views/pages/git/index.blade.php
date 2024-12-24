@extends('layouts.default')
@section('content')
    <div class="row pt-4">
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

    <div class="row d-none" id="loading">
        <div class="text-center py-2">
            <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    @if ($gitRemote === false || $gitRemote === '')
        <x-git.init />
    @else
        @if (!empty($changes))
            <x-git.changes :changes="$changes" />
        @endif

        <x-git.history :history="$history" :gitRemote="$gitRemote" :changes="$changes" />

    @endif
@stop


@push('scriptsFooter')
    <script>
        document.querySelectorAll('[wiki-loading]').forEach(element => {
            element.addEventListener('click', (event) => {
                // Disable clicked element if it's a button
                if (element.tagName === 'BUTTON') {
                    element.disabled = true;
                }

                // Show loading indicator
                const loader = document.querySelector('#loading');
                if (loader) {
                    loader.classList.remove('d-none');
                }

                // Submit form if button is inside form
                if (element.tagName === 'BUTTON' && element.type === 'submit') {
                    event.preventDefault();
                    const form = element.closest('form');
                    if (form) {
                        form.submit();
                    }
                }
            });
        });
    </script>
@endpush
