<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name', 'Gamesgo') }} </title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="Artdevue">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('icon')
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Font Awesome-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/fontawesome.css') }}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/icofont.css') }}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/themify.css') }}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/flag-icon.css') }}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/feather-icon.css') }}">
    <!-- Plugins css start-->
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.css') }}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link id="color" rel="stylesheet" href="{{ asset('assets/css/light-1.css') }}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}">
    @yield('stylesheet')
</head>
<body>
<!-- Loader starts-->
<div class="loader-wrapper">
    <div class="loader loader-7">
        <div class="line line1"></div>
        <div class="line line2"></div>
        <div class="line line3"></div>
    </div>
</div>
<!-- Loader ends-->
<!-- page-wrapper Start-->
<div class="page-wrapper">
    @section('page-wrapper')
        @if (Auth::user())
            <!-- Page Header Start-->
                <div class="page-main-header">
                    <div class="main-header-right row">
                        <div class="main-header-left d-lg-none">
                            <div class="logo-wrapper"><a href="/"><img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'Gamesgo') }}"></a></div>
                        </div>
                        <div class="mobile-sidebar d-block">
                            <div class="media-body text-right switch-sm">
                                <label class="switch">
                                    <input id="sidebar-toggle" type="checkbox" checked="checked"><span class="switch-state"></span>
                                </label>
                            </div>
                        </div>
                        <div class="vertical-mobile-sidebar"><i class="fa fa-bars sidebar-bar"></i></div>
                        <div class="nav-right col left-menu-header">
                            <ul class="nav-menus-left">
                                <li><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>

                                <li>
                                    @if(Auth::user()->isRoleAction('games_edit'))
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle" type="button" data-toggle="dropdown">Быстрое меню</button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('m.games.create') }}">Новая игра</a>
                                            @if(Auth::user()->isRoleAction('genres_view'))
                                            <a class="dropdown-item" href="{{ route('m.games.genres.new') }}">Новый жанр</a>
                                            @endif
                                            @if(Auth::user()->isRoleAction('heroes_view'))
                                            <a class="dropdown-item" href="{{ route('m.games.heroes.get.new') }}">Новый герой</a>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </li>
                            </ul>
                            <div class="d-xl-none mobile-toggle-left pull-right"><i data-feather="more-horizontal"></i></div>
                        </div>
                        <div class="nav-right col pull-right right-menu">
                            <ul class="nav-menus">
                                <li>
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle" type="button" data-toggle="dropdown">
                                <span class="media user-header">
                                    <img class="mr-2 rounded-circle img-35" src="{{ Auth::user()->small_avatar }}" alt="{{ Auth::user()->name }}">
                                    <span class="media-body">
                                        <span class="f-12 f-w-600">{{ Auth::user()->name }}</span>
                                        <span class="d-block">{{ Auth::user()->role_name }}</span>
                                    </span>
                                </span>
                                        </button>
                                        <div class="dropdown-menu p-0">
                                            <ul class="profile-dropdown">
                                                <li class="gradient-primary-1">
                                                    <h6 class="mb-0">{{ Auth::user()->name }}</h6>{{--<span>Admin</span>--}}
                                                </li>
                                                {{--<li><i data-feather="user"> </i>Profile</li>
                                                <li><i data-feather="message-square"> </i>Inbox</li>
                                                <li><i data-feather="file-text"> </i>Taskboard</li>
                                                <li><i data-feather="settings"> </i>Settings</li>--}}
                                                <li>
                                                    <a href="{{ LaravelLocalization::localizeUrl('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> <i data-feather="log-out"> </i>{{ __('auth.logout') }}</a>
                                                    <form id="logout-form" action="{{ LaravelLocalization::localizeUrl('logout') }}" method="POST"
                                                          style="display: none;">
                                                        {{ csrf_field() }}
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="d-lg-none mobile-toggle pull-right"><i data-feather="more-horizontal"></i></div>
                    </div>
                </div>
                <!-- Page Header Ends-->
                <!-- Page Body Start-->
                <div class="page-body-wrapper">
                    <!-- Page Sidebar Start-->
                    <div class="page-sidebar iconcolor-sidebar">
                        <div class="main-header-left d-none d-lg-block">
                            <div class="logo-wrapper"><a href="/"><img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'Gamesgo') }}"></a></div>
                        </div>
                        <div class="sidebar custom-scrollbar">
                            <ul class="sidebar-menu">
                                <li{!! Request::segment(1) == '' ? ' class="active"' : '' !!}><a class="sidebar-header" href="/"><i data-feather="home"></i><span>Главная</span></a></li>
                                @if(Auth::user()->isRoleAction('users_view'))
                                <li{!! Request::segment(1) == 'user' || Request::segment(1) == 'users' ? ' class="active"' : '' !!}><a class="sidebar-header" href="#"><i data-feather="users"></i><span>Пользователи</span><i class="fa fa-angle-right pull-right"></i></a>
                                    <ul class="sidebar-submenu">
                                        <li><a{!! Request::segment(1) == 'users' && Request::segment(2) == '' ? ' class="active"' : '' !!} href="{{ route('m.users') }}"><i class="fa fa-circle"></i>Все пользователи</a></li>
                                        @if(Auth::user()->isRoleAction('users_create'))
                                        <li><a{!! Request::segment(1) == 'user' && Request::segment(2) == 'new' ? ' class="active"' : '' !!} href="{{ route('m.users.new') }}"><i class="fa fa-circle"></i>Новый пользователь</a></li>
                                        @endif
                                    </ul>
                                </li>
                                @endif
                                @if(Auth::user()->isRoleAction('games_view') || Auth::user()->isRoleAction('games_viewonly'))
                                <li{!! Request::segment(1) == 'games' ? ' class="active"' : '' !!}><a class="sidebar-header" href="#"><i data-feather="box"></i><span>Игры</span><i class="fa fa-angle-right pull-right"></i></a>
                                    <ul class="sidebar-submenu">
                                        <li><a{!! Request::segment(1) == 'games' && Request::segment(2) == '' ? ' class="active"' : '' !!} href="{{ route('m.games.index') }}"><i class="fa fa-circle"></i>Игры</a></li>
                                        {{--<li><a{!! Request::segment(1) == 'games' && Request::segment(2) == 'create' ? ' class="active"' : '' !!} href="{{ route('m.games.create') }}"><i class="fa fa-circle"></i>Новая игра</a></li>--}}
                                        {{--<li><a{!! Request::segment(1) == 'games' && Request::segment(2) == 'categories' ? ' class="active"' : '' !!} href="{{ route('m.games.categories') }}"><i class="fa fa-circle"></i>Категории</a></li>
                                        <li><a{!! Request::segment(1) == 'games' && Request::segment(2) == 'category' && Request::segment(3) == 'create' ? ' class="active"' : '' !!} href="{{ route('m.games.category.create') }}"><i class="fa fa-circle"></i>Новая категория</a></li>--}}
                                        @if(Auth::user()->isRoleAction('genres_view'))
                                        <li><a{!! Request::segment(1) == 'games' && Request::segment(2) == 'genres' ? ' class="active"' : '' !!} href="{{ route('m.games.genres') }}"><i class="fa fa-circle"></i>Жанры</a></li>
                                        @endif
                                        @if(Auth::user()->isRoleAction('heroes_view'))
                                        <li><a{!! Request::segment(1) == 'games' && Request::segment(2) == 'heroes' ? ' class="active"' : '' !!} href="{{ route('m.games.heroes') }}"><i class="fa fa-circle"></i>Герои</a></li>
                                        @endif
                                    </ul>
                                </li>
                                @endif
                                @if(Auth::user()->isRoleAction('comments_view'))
                                <li{!! Request::segment(1) == 'comments' ? ' class="active"' : '' !!}><a class="sidebar-header" href="{{ route('m.comments.index') }}"><i data-feather="message-square"></i><span>Комментарии</span><span class="badge badge-pill gradient-success feedbackcount">{{\App\Models\Comment::where('confirmed', '=', 0)->count()}}</span></a></li>
                                @endif
                                @if(Auth::user()->isRoleAction('devel_view'))
                                <li{!! Request::segment(1) == 'developers' ? ' class="active"' : '' !!}><a class="sidebar-header" href="{{ route('m.developers.index') }}"><i data-feather="codepen"></i><span>Разработчики</span></a></li>
                                @endif
                                    {{--<li{!! Request::segment(1) == 'game_features' ? ' class="active"' : '' !!}><a class="sidebar-header" href="{{ route('m.game_features.index') }}"><i data-feather="slack"></i><span>Характеристики игры</span></a></li>--}}
                                @if(Auth::user()->role == 1)
                                    <li{!! Request::segment(1) == 'command' ? ' class="active"' : '' !!}><a class="sidebar-header" href="{{ route('m.command.index') }}"><i data-feather="command"></i><span>Управление</span></a></li>
                                    <li{!! Request::segment(1) == 'pages' ? ' class="active"' : '' !!}><a class="sidebar-header" href="{{ route('m.pages.index') }}"><i data-feather="layout"></i><span>Страницы</span></a></li>
                                    <li{!! Request::segment(1) == 'seo-page' ? ' class="active"' : '' !!}><a class="sidebar-header" href="{{ route('m.seopage.index') }}"><i data-feather="file-text"></i><span>СЕО страницы</span></a></li>
                                    <li{!! Request::segment(1) == 'feedbacks' ? ' class="active"' : '' !!}><a class="sidebar-header" href="{{ route('m.feedbacks.index') }}"><i data-feather="mail"></i><span>Сообщения</span><span class="badge badge-pill gradient-success feedbackcount">{{ \App\Models\Feedback::whereView(0)->count() }}</span></a></li>
                                    <li{!! Request::segment(1) == 'search-word' ? ' class="active"' : '' !!}><a class="sidebar-header" href="{{ route('m.search.word') }}"><i data-feather="search"></i><span>Поисковые запросы</span></a></li>
                                    <li{!! Request::segment(1) == 'popups' ? ' class="active"' : '' !!}><a class="sidebar-header" href="{{ route('m.popups.index') }}"><i data-feather="book"></i><span>PopUo окна</span></a></li>
                                    <li{!! Request::segment(1) == 'setting' ? ' class="active"' : '' !!}><a class="sidebar-header" href="#"><i data-feather="settings"></i><span>Система</span><i class="fa fa-angle-right pull-right"></i></a>
                                        <ul class="sidebar-submenu">
                                            <li><a{!! Request::segment(1) == 'setting' && Request::segment(2) == 'language' ? ' class="active"' : '' !!} href="{{ route('m.setting.language') }}"><i class="fa fa-circle"></i>Языки</a></li>
                                            <li><a{!! Request::segment(1) == 'setting' && Request::segment(2) == 'translation' ? ' class="active"' : '' !!} href="{{ route('m.setting.translation') }}"><i class="fa fa-circle"></i>Редактор языка</a></li>
                                        </ul>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <!-- Page Sidebar Ends-->
                    <div class="page-body">
                        @yield('content')
                    </div>
                    <!-- footer start-->
                    <footer class="footer">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6 footer-copyright">
                                    <p class="mb-0">Copyright {{ date('Y') }} © {{ config('app.name', 'Gamesgo') }} All rights reserved.</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="pull-right mb-0">Hand crafted & made with<i class="fa fa-heart"></i></p>
                                </div>
                            </div>
                        </div>
                    </footer>
                </div>
        @endif
    @show
</div>
<!-- latest jquery-->
<script src="{{ asset('assets/js/jquery-3.2.1.min.js') }}"></script>
<!-- Bootstrap js-->
<script src="{{ asset('assets/js/bootstrap/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap/bootstrap.js') }}"></script>
<!-- feather icon js-->
<script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script>
<!-- Sidebar jquery-->
<script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
<script src="{{ asset('assets/js/config.js') }}"></script>
<!-- Plugins JS start-->
<script src="{{ asset('assets/js/chat-menu.js') }}"></script>
<script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
<script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('assets/js/js-cookie/js.cookie.min.js') }}"></script>
<!-- Plugins JS Ends-->
@yield('script')
<!-- Theme js-->
<script src="{{ asset('assets/js/script.js') }}"></script>
{{--<script src="{{ asset('assets/js/theme-customizer/customizer.js') }}"></script>--}}
<!-- login js-->
<!-- Plugin used-->
@stack('ls')
</body>
</html>
