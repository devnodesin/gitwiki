@extends('layouts.default')
@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row pt-4">
        <div class="col-12">
            @if (Auth::user()->role === App\Enums\UserRoles::Admin)
                <div class="mb-3">
                    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#addUser">
                        Add User
                    </button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            @if (Auth::user()->role === App\Enums\UserRoles::Admin)
                                <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role->value }}</td>
                                @if (Auth::user()->role === App\Enums\UserRoles::Admin)
                                    <td>
                                        <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editUser{{ $user->id }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('user.delete', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                onclick="return confirm('Are you sure you want to delete this user?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>

                            @if (Auth::user()->role === App\Enums\UserRoles::Admin)
                                <!-- Edit User Modal -->
                                <div class="modal fade" id="editUser{{ $user->id }}" tabindex="-1"
                                    aria-labelledby="editUserLabel{{ $user->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('user.update', $user->id) }}" method="POST">
                                                @csrf
                                                @method('POST')
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editUserLabel{{ $user->id }}">
                                                        Edit User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="name" class="form-label">Name</label>
                                                        <input type="text"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            id="name" name="name"
                                                            value="{{ old('name', $user->name) }}">
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="email"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            id="email" name="email"
                                                            value="{{ old('email', $user->email) }}">
                                                        @error('email')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="role" class="form-label">Role</label>
                                                        <select class="form-select @error('role') is-invalid @enderror"
                                                            id="role" name="role">
                                                            @foreach (App\Enums\UserRoles::cases() as $role)
                                                                <option value="{{ $role->value }}"
                                                                    {{ $user->role === $role ? 'selected' : '' }}>
                                                                    {{ $role->value }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('role')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="password" class="form-label">Password (leave
                                                            blank to keep current)</label>
                                                        <input type="password"
                                                            class="form-control @error('password') is-invalid @enderror"
                                                            id="password" name="password">
                                                        @error('password')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-dark">Save
                                                        changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="{{ Auth::user()->role === App\Enums\UserRoles::Admin ? '4' : '3' }}"
                                    class="text-center">No users found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    @if (Auth::user()->role === App\Enums\UserRoles::Admin)
        <!-- Add User Modal -->
        <div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('user.add') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserLabel">Add New User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role"
                                    name="role">
                                    <option value="">Select Role</option>
                                    @foreach (App\Enums\UserRoles::cases() as $role)
                                        <option value="{{ $role->value }}"
                                            {{ old('role') === $role->value ? 'selected' : '' }}>
                                            {{ $role->value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-dark">Create User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@stop
