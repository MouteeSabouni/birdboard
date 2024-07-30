<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-gray-mid">
    <div id="app">
        <nav class="bg-white">
            <div class="container mx-auto">
                <div class="flex justify-between items-center py-2">
                    <h1>
                        <a class="navbar-brand" href="{{ url('/') }}">
                            <img src="/images/logo.png" alt="Birdboard" class="w-1/12" />
                        </a>
                    </h1>

                    <div class="flex items-center">
                        @guest
                            <a class="nav-link hover:bg-blue-100 rounded-xl py-2 px-3 hover:font-bold transition-all" href="{{ route('login') }}">{{ __('Login') }}</a>

                            <a class="nav-link hover:bg-blue-100 rounded-xl py-2 px-3 hover:font-bold transition-all" href="{{ route('register') }}">{{ __('Register') }}</a>
                        @else
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item group" href="/projects">
                                    <span class="group-hover:text-base transition-all group-hover:font-bold">
                                        {{ __('Projects') }}
                                    </span>
                                </a>

                                <a class="dropdown-item group" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                    <span class="group-hover:text-base transition-all group-hover:font-bold">
                                        {{ __('Logout') }}
                                    </span>
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <main class="container mx-auto py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
