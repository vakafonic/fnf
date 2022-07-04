<div class="menu">
    <div class="menu__body">
        <div class="menu__title">
            Menu
            <svg class="menu__close" fill="none" height="40" viewBox="0 0 40 40" width="40"
                 xmlns="http://www.w3.org/2000/svg">
                <circle cx="20" cy="20" fill="#EA0042" r="20" />
                <path d="M26 13L14 25" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                <path d="M14 13L26 25" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
            </svg>
        </div>
        <ul class="menu__list">
            <li class="menu__list-item">
                <a class="menu__link"  href="{{ route('mods') }}">{{$lang['FNF_mods']}}</a>
            </li>
            <li class="menu__list-item">
                <a class="menu__link"  href="{{ route('mods', ['new' => 1]) }}">{{$lang['New_mods']}}</a>
            </li>
            <li class="menu__list-item spollers-block _spollers _one">
                <div class="spollers-block__item">
                    <div class="spollers-block__title _spoller">
                        {{$lang['Download_FNF']}}
                        <svg fill="none" height="8" viewBox="0 0 12 8" width="12" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 1.5L6 6.5L11 1.5" stroke="white" stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2" />
                        </svg>
                    </div>
                    <div class="spollers-block__body">
                        <ul class="menu__sublist">
                            @if($current_locale = \App\Models\Language::whereCode(\Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale())->first()) @endif
                            @foreach(\App\Models\Page::getTopMenuDownload($current_locale->id ?? 1) as $url => $page)
                                <li class="menu__sublist-item">
                                    <a class="menu__sublist-link" href="{{$url}}">{{$page->menu_name}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            <li class="menu__list-item">
                <a class="menu__link" href="{{ route('week-7') }}">{{$lang['Week_7']}}</a>
            </li>
            {{-- TODO replace with regular links --}}
            <select class="menu__list-item menu__lang" name="form[]">
                <option selected="selected" value="English">English</option>
                <option value="Русский">Русский</option>
                <option value="Українська">Українська</option>
            </select>
        </ul>
    </div>
    <div class="menu__overlay"></div>
</div>