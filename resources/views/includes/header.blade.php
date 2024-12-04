<div class="container-fluid border-bottom">
    <div class="row">
        <div class="d-flex align-items-center">
            <div class="p-2">
                <a href="{{ route('home') }}" class="link-body-emphasis text-decoration-none">
                    <i class="bi bi-house-fill fs-3"></i>
                </a>
            </div>
            @isset($title['title'])
            <div class="p-2 align-self-center">
                <p class="fs-3 m-0 p-0">
                    @isset($title['title_url'])
                        <a class="text-decoration-none" href="{{ $title['title_url'] }}">{{ ucfirst($title['title']) }}</a>
                    @else
                        {{ ucfirst($title['title']) }}
                    @endisset

                    @isset($title['sub_title'])
                        : {{ $title['sub_title'] }}
                    @endisset
                </p>
            </div>
            @endisset

            <div class="ms-auto p-2">
                <div class="flex-shrink-0 dropdown">
                    <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-square fs-3"></i>
                    </a>
                    <ul class="dropdown-menu text-small shadow">
                        @if (Auth::check())
                            <li><a class="dropdown-item" href="{{ route('profile') }}">Profile</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            @if (Auth::check() && Auth::user()->role === App\Enums\UserRoles::Admin)
                                <li><a class="dropdown-item" href="{{ route('user.list') }}">Manage Users</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('logout') }}">Sign out</a></li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('login') }}">Login</a></li>
                        @endif
                    </ul>
                </div>
            </div>

        </div>


    </div> <!-- row -->
</div> <!-- container -->
