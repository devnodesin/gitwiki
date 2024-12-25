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

    <div class="card mt-3">
        <div class="card-header">
            <h5 class="card-title mb-0">GitWiki Settings</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf

                @foreach ($settings as $setting)
                    <div class="mb-3 row">
                        <label for="{{ $setting['key'] }}" class="col-sm-2 form-label">
                            {{ ucwords(str_replace('_', ' ', $setting['key'])) }}
                        </label>

                        <div class="col-sm-10">
                            @switch($setting['value_type'])
                                @case('boolean')
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="{{ $setting['key'] }}"
                                            id="{{ $setting['key'] }}" value="1" {{ $setting['value'] ? 'checked' : '' }}
                                            {{ isset($setting['edit']) && !$setting['edit'] ? 'disabled' : '' }}>
                                    </div>
                                @break

                                @case('integer')
                                @case('float')
                                    <input type="number" class="form-control" name="{{ $setting['key'] }}"
                                        id="{{ $setting['key'] }}" value="{{ $setting['value'] }}"
                                        step="{{ $setting['value_type'] === 'float' ? '0.01' : '1' }}"
                                        {{ isset($setting['edit']) && !$setting['edit'] ? 'disabled' : '' }}>
                                @break

                                @default
                                    <input type="text" class="form-control" name="{{ $setting['key'] }}"
                                        id="{{ $setting['key'] }}" value="{{ $setting['value'] }}"
                                        {{ isset($setting['edit']) && !$setting['edit'] ? 'disabled' : '' }}>
                            @endswitch
                        </div>

                        @error($setting['key'])
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                @endforeach

                <div class="text-end">
                    <button type="submit" class="btn btn-dark">
                        Save Settings
                    </button>
                </div>
            </form>
        </div>
    </div>


@stop
