@extends('layouts.default')
@section('content')
    <div class="text-center my-5 py-5">
        @if (Route::has('login'))
            <nav class="-mx-3 flex flex-1 justify-end">

                <div class="d-flex justify-content-center align-items-center">
                    <div class="p-5 bg-white border rounded shadow">
                        <x-login />
                    </div>
                </div>

            </nav>
        @endif
    </div>
@stop
