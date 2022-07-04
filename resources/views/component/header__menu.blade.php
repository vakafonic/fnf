<nav class="header__menu">
    <ul class="header__menu-list">

        <li class="header__menu-item">
            <a class="header__menu-link" href="{{ route('mods') }}">{{$lang['FNF_mods']}}</a>
        </li>
        <li class="header__menu-item">
            <a class="header__menu-link" href="{{ route('new-mods') }}">{{$lang['New_mods']}}</a>
        </li>

        <li class="header__menu-item sublist">
            <div class="header__menu-link">
                {{$lang['Download_FNF']}}

                <svg fill="none" height="8" viewBox="0 0 12 8" width="12" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 1.5L6 6.5L11 1.5" stroke="white" stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2" />
                </svg>
            </div>
            <ul class="sublist__body">
                @if($current_locale = \App\Models\Language::whereCode(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale())->first()) @endif
                @foreach(\App\Models\Page::getTopMenuDownload($current_locale->id ?? 1) as $url => $page)
                    <li class="sublist__body-item">
                        <a class="sublist__body-link" href="{{$url}}">{{$page->menu_name}}</a>
                    </li>
                @endforeach
            </ul>
        </li>
        <li class="header__menu-item">
            <a class="header__menu-link" href="{{ route('week-7') }}">{{$lang['Week_7']}}</a>
        </li>
    </ul>
</nav>
