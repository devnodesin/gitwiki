<!doctype html>
<html lang="en" {{ isset($darkMode) ? 'data-bs-theme=dark' : 'class=bg-ivory' }}>

<head>
    @include('includes.head')
</head>

<body @class(['bg-ivory'=> !isset($darkMode)])>
    <header>
        @includeUnless(isset($noHeader), 'includes.header')
    </header>

    <div @class([
            'container' => !isset($containerFluid),
            'container-fluid' => isset($containerFluid),
        ])>
        <main>
            @yield('content')
        </main>

        <footer>
            @includeUnless(isset($noFooter), 'includes.footer')
        </footer>
    </div>

    @vite(['resources/js/app.js'])
    <!-- Page Specific Scripts -->
    @stack('scriptsFooter')
</body>

</html>
