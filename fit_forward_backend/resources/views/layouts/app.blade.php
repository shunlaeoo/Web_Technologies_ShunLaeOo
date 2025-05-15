<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FitForward Backend') }} Backend</title>

    <!-- Favicon -->
    <link href="{{ asset('image/icon.png') }}" rel="icon">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    <!-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) -->
</head>
<body>
    <div id="app">
        @include('layouts.header')
        @guest
            <main class="py-4" style="min-height: 86vh;">
                @yield('content')
            </main>
        @else
            <div class="d-flex">
                <!-- Sidebar -->
                @include('layouts.sidebar')
                <main class="w-100">
                    @yield('content')
                </main>
            </div>
        @endguest
        <footer class="bg-black">
            <div class="text-center text-white py-2">
                &copy; 2025 FIT-FORWARD. All rights reserved.
            </div>
        </footer>
    </div>

    @yield('script')

    <!-- Bootstrap Bundle with Popper (for Bootstrap JS components like modals, tooltips, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- CK Editor -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#description'))
            .catch(error => {
            console.error(error);
            });
    </script>

</body>
</html>
