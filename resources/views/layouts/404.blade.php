<!doctype html>
<html lang="en" {{ isset($darkMode) ? 'data-bs-theme=dark' : 'class=bg-ivory' }}>

<head>
    @include('includes.head')
</head>

<body @class(['bg-ivory'=> !isset($darkMode)])>

    <div class="container">
        <main>
            @yield('content')
        </main>

    </div>

    <!-- Core Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
        crossorigin="anonymous"></script>
    
    <!-- Page Specific Scripts -->
    @stack('scriptsFooter')
</body>

</html>
