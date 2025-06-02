<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @use('AnisAronno\MediaHelper\Facades\Media')

    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="{{ asset('images/logo-small.svg') }}" type="image/svg+xml" />

    <title>@yield('title', 'Inusittá')</title>


    <meta name="description"
        content="Admin Toolkit is a modern admin dashboard template based on Tailwindcss. It comes with a variety of useful ui components and pre-built pages" />


    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

    <script>
        if (
            localStorage.getItem('theme') === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <script>
        window.currentRoute = '{{ Route::currentRouteName() }}';
    </script>

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

</head>

<body>
    <div id="app">
        <!-- Sidebar Starts -->
        <x-sidebar />
        <!-- Sidebar Ends -->

        <!-- Wrapper Starts -->
        <div class="wrapper">
            <!-- Header Starts -->
            <x-header />
            <!-- Header Ends -->

            <!-- Page Content Starts -->
            <div class="content">
                <!-- Main Content Starts -->
                <main class="container flex-grow p-2 sm:p-4 md:p-6">
                    {{ $slot }}
                </main>
                <!-- Main Content Ends -->

                <!-- Footer Starts -->
                <x-footer />
                <!-- Footer Ends -->
            </div>
            <!-- Page Content Ends -->
        </div>
        <!-- Wrapper Ends -->

        <!-- Search Modal Start -->
        <x-search-modal />
        <!-- Search Modal Ends -->
    </div>
</body>

</html>
