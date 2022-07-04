{{ Form::open([
    'route' => 'search_prev',
    'id' => 'searchForm',
    'method' => 'post',
    'class' => 'form header__form-search'
]) }}
{!! Form::text(
    'search',
     old('search'),
     [
         'class' => 'header__search-input input',
         'clearable' => 'clearable',
         'autocomplete' => 'off',
         'required' => 'required',
         'placeholder' => $lang['looking_for']
     ]
) !!}
<button class="header__form-search-btn header__form-search-btn_back" type="button">
    <svg fill="none" height="20" viewBox="0 0 20 20" width="20" xmlns="http://www.w3.org/2000/svg">
        <path d="M15.8332 10H4.1665" stroke="black" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
        <path d="M9.99984 15.8333L4.1665 9.99996L9.99984 4.16663" stroke="black" stroke-linecap="round"
              stroke-linejoin="round" stroke-width="2" />
    </svg>
</button>
<button class="header__form-search-btn header__form-search-btn_clear" type="button">
    <svg fill="none" height="20" viewBox="0 0 20 20" width="20" xmlns="http://www.w3.org/2000/svg">
        <path d="M15 5L5 15" stroke="#777E90" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
        <path d="M5 5L15 15" stroke="#777E90" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
    </svg>
</button>
<button class="header__form-search-btn header__form-search-btn_submit" type="submit">
    <svg fill="none" height="20" viewBox="0 0 20 20" width="20" xmlns="http://www.w3.org/2000/svg">
        <path
                d="M9.16667 15.8333C12.8486 15.8333 15.8333 12.8486 15.8333 9.16667C15.8333 5.48477 12.8486 2.5 9.16667 2.5C5.48477 2.5 2.5 5.48477 2.5 9.16667C2.5 12.8486 5.48477 15.8333 9.16667 15.8333Z"
                stroke="#777E90" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
        <path d="M17.5 17.5L13.875 13.875" stroke="#777E90" stroke-linecap="round" stroke-linejoin="round"
              stroke-width="2" />
    </svg>
</button>

<div class="header__form-search-result search-result">
    <div class="search-result__body">
    </div>
</div>
{{ Form::close() }}

<div class="header__search-btn-mob">
    <svg fill="none" height="20" viewBox="0 0 20 20" width="20" xmlns="http://www.w3.org/2000/svg">
        <path
                d="M9.16667 15.8333C12.8486 15.8333 15.8333 12.8486 15.8333 9.16667C15.8333 5.48477 12.8486 2.5 9.16667 2.5C5.48477 2.5 2.5 5.48477 2.5 9.16667C2.5 12.8486 5.48477 15.8333 9.16667 15.8333Z"
                stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
        <path d="M17.5 17.5L13.875 13.875" stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round"
              stroke-width="2" />
    </svg>
</div>


