@extends('layouts.default')
@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @endif

    <div class="row pt-4">
        <h3>Profile Information</h3>
    </div>

    <div class="row pt-2">
        <div class="col-12 col-md-6">
            <form action="{{ route('user.profile-update') }}" method="POST">
                @csrf
                <input type="hidden" name="update_type" value="profile">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" value="{{ old('name', $user->name) }}">
                    <label for="name">Name</label>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-floating mb-3">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" value="{{ old('email', $user->email) }}">
                    <label for="email">Email address</label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-dark">Update Profile</button>
            </form>
        </div>
    </div>

    <div class="row pt-4">
        <h3>Change Password</h3>
    </div>

    <div class="row pt-2">
        <div class="col-12 col-md-6">
            <form action="{{ route('user.profile-update') }}" method="POST">
                @csrf
                <input type="hidden" name="update_type" value="password">
                <div class="form-floating mb-3">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" placeholder="Enter new password">
                    <label for="password">New Password</label>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-dark">Update Password</button>
            </form>
        </div>
    </div>

    <div class="row pt-4">
        <div class="col-12 col-md-6">
            <a class="btn btn-dark" href="{{ route('logout') }}">Logout</a>
        </div>
    </div>
@stop
