<div class="b-nav">
    @if($patch = $paginator->toArray()['path']) @endif
    <a class="b-nav__item b-nav__item_prev button button_dark" @if (!$paginator->onFirstPage()) href="{{ $patch }}{{ ($paginator->currentPage() - 1) > 1 ?  ($paginator->currentPage() - 1) . '/' : '' }}" @endif>
        <svg width="8" height="12" viewBox="0 0 8 12" fill="none">
            <path d="M6.5 11L1.5 6L6.5 1" stroke="#777E90" stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round" />
        </svg>
        {{$lang['Previous']}}
    </a>

    @foreach(generateSmartPaginationPageNumbers($paginator->total(), $paginator->perPage(), $paginator->currentPage(), 5) as $page_ar)
        @if ($page_ar == $paginator->currentPage())
            <a class="b-nav__item button button_dark _active">{{ $page_ar }}</a>
        @else
            @if(!is_numeric($page_ar) && $page_ar = substr($page_ar,3))
                <a class="b-nav__item button button_dark" href="{{ $patch }}{{ $page_ar > 1 ? $page_ar . '/' : '' }}">...</a>
            @else
                <a class="b-nav__item button button_dark" href="{{ $patch }}{{ $page_ar > 1 ? $page_ar . '/' : '' }}">{{ $page_ar }}</a>
            @endif
        @endif
    @endforeach

    <a class="b-nav__item b-nav__item_next button button_dark" @if ($paginator->hasMorePages()) href="{{ $patch }}{{ $paginator->currentPage() + 1 }}/" @endif>
        {{$lang['Next']}}
        <svg width="8" height="12" viewBox="0 0 8 12" fill="none">
            <path d="M1.5 11L6.5 6L1.5 1" stroke="#777E90" stroke-width="2" stroke-linecap="round"
                  stroke-linejoin="round" />
        </svg>
    </a>
</div>


<div class="pagination">
    @if($patch = $paginator->toArray()['path']) @endif
    <ul class="pagination-list">
        <!-- Previous Page Link -->
        @if ($paginator->onFirstPage())
            <li class="pagination-btn is-disabled is-first"><span class="icon-arrow-left-light"></span></li>
        @else
            <li class="pagination-item is-first">
                <a class="pagination-btn is-active" href="{{ $patch }}{{ ($paginator->currentPage() - 1) > 1 ?  ($paginator->currentPage() - 1) . '/' : '' }}"
                   onclick="window.location.href = $(this).attr('href');"
                >
                    <span class="icon-arrow-left-light"></span>
                </a>
            </li>
        @endif

        @if ($paginator->hasMorePages())
            <li class="pagination-item is-last">
                <a class="pagination-btn is-active"
                   href="{{ $patch }}{{ $paginator->currentPage() + 1 }}/"
                   onclick="window.location.href = $(this).attr('href');">
                    <span class="icon-arrow-right-light"></span>
                </a>
            </li>
        @else
            <li class="pagination-btn is-disabled is-last"><span class="icon-arrow-right-light"></span></li>
        @endif
    </ul>
</div>
