<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <!-- Variables -->
    <title>@yield('seo_title')</title>
    <meta name="description" content="@yield('seo_description')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Static -->
    <meta charset="UTF-8">
    <meta name="format-detection" content="telephone=no">
    <link rel="shortcut icon" href="favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/added.css') }}">

    <!-- Prefetch TODO add addresses -->
    <link rel="dns-prefetch" href="https://quantcast.mgr.consensu.org/" />
    <link rel="preconnect" href="https://quantcast.mgr.consensu.org/" />
    <link rel="dns-prefetch" href="https://img.gamesgo.net/" />
    <link rel="preconnect" href="https://img.gamesgo.net/" />
    <link rel="dns-prefetch" href="https://mc.yandex.ru/" />
    <link rel="preconnect" href="https://mc.yandex.ru/" />
    <link rel="dns-prefetch" href="https://www.googletagmanager.com/" />
    <link rel="preconnect" href="https://www.googletagmanager.com/" />
    <link rel="dns-prefetch" href="https://www.google-analytics.com/" />
    <link rel="preconnect" href="https://www.google-analytics.com/" />
    <link rel="dns-prefetch" href="https://fonts.googleapis.com/" />
    <link rel="preconnect" href="https://fonts.googleapis.com/" />

    <!-- Seo -->
    @if(!empty(request()->get('page')))
        <meta name="robots" content="noindex, follow">
    @else
        @if($_SERVER['REQUEST_URI'] == '/' || $_SERVER['REQUEST_URI'] == '/home'
            || $_SERVER['REQUEST_URI'] == '/ru/' || $_SERVER['REQUEST_URI'] == '/ru/home'
            || $_SERVER['REQUEST_URI'] == '/uk/' || $_SERVER['REQUEST_URI'] == '/uk/home')
            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <link rel="alternate" hreflang="{{ $localeCode }}" href="{{ str_replace('/en', '', LaravelLocalization::getLocalizedURL($localeCode, null, [], true)) }}/" />
            @endforeach
        @else
            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <link rel="alternate" hreflang="{{ $localeCode }}" href="{{ str_replace('en/', '', LaravelLocalization::getLocalizedURL($localeCode, null, [], true)) }}/" />
            @endforeach
        @endif
    @section('canonical')
        <link rel="canonical" href="{{ !empty(request()->get('page')) ? str_replace_last('/' . request()->get('page') . '/', '/', url()->current()) : (url()->current() == config('app.url') ? trim(url()->current(), '/') : url()->current()) }}" />
    @show
    @endif
    @include('icon')

</head>

<body>
<div class="wrapper">
    <header class="header">
        <div class="header__content _container">
            <div class="menu__icon icon-menu">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <a class="header__logo" href="{{ rtrim(LaravelLocalization::localizeUrl('/'), '/') }}/">
                <picture><source srcset="{{ Storage::disk('app')->url('images/logo.svg') }}" type="image/webp"><img alt="{{ config('app.name', 'Friday Night Funkin') }}" src="{{ Storage::disk('app')->url('images/logo.svg') }}"></picture>
            </a>
            @include('component.header__menu')
            @include('include.header__search')
            <div class="langs">
                <button class="langs-opener" type="button">
                    {{ LaravelLocalization::getCurrentLocale() }}
                    <span class="icon-arrow-down">
                      <svg width="12" height="8" viewBox="0 0 12 8" fill="none">
                        <path d="M1 1.5L6 6.5L11 1.5" stroke="#EA0042" stroke-width="2" stroke-linecap="round"
                              stroke-linejoin="round" />
                      </svg>
                </span>
                </button>
                <ul class="langs-list">
                     @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        @if(app()->getLocale() == $localeCode) @continue @endif
                        @if($localeCode == 'en')
                            <li class="langs-item">
                                <a class="langs-link"
                                   href="{{str_replace('/en', '', LaravelLocalization::getLocalizedURL($localeCode, null, [], true))}}/">{{ $properties['native'] }}</a>
                            </li>
                        @else
                            <li class="langs-item">
                                <a class="langs-link"
                                   href="{{LaravelLocalization::getLocalizedURL($localeCode, null, [], true)}}/">{{ $properties['native'] }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </header>
    <div class="header__form-search-overlay"></div>
    @include('component.header__menu_mobile')
    @yield('content')

    @include('component.modals')
    @include('include.footer')
</div>
<script type="application/javascript">
    var mailLang = {
        noSuggestionNotice: '{{ $lang['no_suggestion_notice'] }}',
        show_all: '{{ $lang['show_all'] }}',
        route_home: '{{ route('index') }}',
        route_search_ajax: '{{ route('search_ajax') }}'
    };
</script>
<script src="{{ asset('js/vendors.min.js') }}"></script>
<script src="{{ asset('js/assets.min.js') }}"></script>
<script src="{{ asset('js/app.min.js') }}"></script>
<script src="{{ asset('js/added.js') }}"></script>
@yield('script')
</body>
</html>