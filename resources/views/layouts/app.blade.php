<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ setting('site.title') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{asset('storage/logo.svg')}}" sizes="any" type="image/svg+xml">
    <link rel="mask-icon" href="{{asset('storage/logo.svg')}}" color="#000000" sizes="any" type="image/svg+xml">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/bootstrap.js'])
</head>
<body>
    <div id="app">
        @auth
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm p-0">
            <div class="container d-flex justify-content-between p-1">
                <a href="/"><img src="{{asset('storage/full-logo.svg')}}" alt="Logo" class="image" style="width: 200px; height: 50px; object-fit: contain;"></a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <div class="navbar-nav me-auto">
                        @yield('navbar-left')
                    </div>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}  <img src="{{asset('storage/' . Auth::user()->avatar)}}" class="image rounded-circle" style="width: 35px;"
                                alt="Avatar" />
                            </a>

                            <div class="dropdown-menu dropdown-menu-end {{\Browser::isMobile() ? 'show' : ''}}" aria-labelledby="navbarDropdown">

                                <a class="dropdown-item" href="{{ localizedRoute('profile.index') }}">
                                    {{ __('Profile') }}
                                </a>

                                @if (!auth()->user()->isCoworker())
                                <a class="dropdown-item" href="{{ localizedRoute('record.create') }}">
                                    {{ __('Online Record') }}
                                </a>
                                @endif

                                <a class="dropdown-item" href="{{ localizedRoute('record.index') }}" data-set-tz>
                                    @if (!auth()->user()->isCoworker())
                                        {{ __('My Records') }}
                                    @else
                                        {{ __('Records') }}
                                    @endif
                                </a>

                                @if(auth()->user()->isAdmin())
                                    <a class="dropdown-item" href="{{ localizedRoute('voyager.dashboard') }}" target="_blank">
                                        {{ __('Admin Panel') }}
                                    </a>
                                @endif

                                <div class="accordion" id="accordionExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                          <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-controls="collapseOne">
                                            {{__("Languages")}}
                                          </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                          <div class="accordion-body py-2">
                                            @php
                                                $currentRoute = Illuminate\Support\Facades\Route::currentRouteName();
                                                $locales = config('app.supported_languages');
                                            @endphp
                                            <ul>
                                                @foreach ($locales as $locale)
                                                    <li style="list-style-type: {{$locale === app()->getLocale() ? 'disc' : 'circle'}}"><a
                                                        href="{{localizedRoute($currentRoute, [], $locale)}}"
                                                        class="link-dark text-decoration-none d-block p-2 p-md-1">{{__($locale)}}</a></li>
                                                @endforeach
                                            </ul>
                                          </div>
                                        </div>
                                    </div>
                                </div>

                                <a class="dropdown-item" href="{{ localizedRoute('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ localizedRoute('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        @endauth

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', function() {
            var tz = Intl.DateTimeFormat().resolvedOptions().timeZone
            document.querySelectorAll('[data-set-tz]')
                .forEach(element => {
                    var url = new URL(element.href)
                    url.searchParams.append('tz', tz)
                    element.href = url.toString()
                });

            document.querySelectorAll('.accordion')
                .forEach(element => {
                    element.addEventListener('click', function (e) {
                        if(e.target.tagName.toLowerCase() !== 'a') {
                            e.stopPropagation()
                            e.preventDefault()
                            return false
                        }
                    })
                });
        })
    </script>
    @yield('scripts')
</body>
</html>
