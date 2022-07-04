<div class="pagination">
    <div class="pagination__wrap">
        <!-- Previous Page Link -->
        @if ($paginator->onFirstPage())
            <p class="pagination__button"><span class="icon-arrow-left"></span></p>
        @else
            <a onclick="gameComments.loadPage({{ $paginator->currentPage() - 1 }})" href="javascript:void(0)" class="pagination__button isActive"><span class="icon-arrow-left"></span></a>
        @endif
        <!-- Pagination Elements -->
        @foreach(generateSmartPaginationPageNumbers($paginator->total(), $paginator->perPage(), $paginator->currentPage(), 5) as $page_ar)
            @if ($page_ar == $paginator->currentPage())
                <p class="pagination__link isActive">{{ $page_ar }}</p>
            @else
                @if(!is_numeric($page_ar) && $page_ar = substr($page_ar,3))
                    <a onclick="gameComments.loadPage({{ $page_ar }})" data-page="{{ $page_ar }}" href="javascript:void(0)" class="pagination__link">...</a>
                @else
                    <a onclick="gameComments.loadPage({{ $page_ar }})" data-page="{{ $page_ar }}" href="javascript:void(0)" class="pagination__link">{{ $page_ar }}</a>
                @endif
            @endif
        @endforeach
        {{--@foreach ($elements as $element)
            @foreach ($element as $page => $url)
                @if ($paginator->currentPage() > 4 && $page === 2)
                    <p class="pagination__link">...</p>
                @endif
                @if ($page == $paginator->currentPage())
                    <p class="pagination__link isActive">{{ $page }}</p>
                @elseif ($page === $paginator->currentPage() + 1 || $page === $paginator->currentPage() + 2 || $page === $paginator->currentPage() - 1 || $page === $paginator->currentPage() - 2 || $page === $paginator->lastPage() || $page === 1)
                    <a onclick="gameComments.loadPage({{ $page }})" data-page="{{ $page }}" href="javascript:void(0)" class="pagination__link">{{ $page }}</a>
                @endif
                @if ($paginator->currentPage() < $paginator->lastPage() - 3 && $page === $paginator->lastPage() - 1)
                    <p class="pagination__link">...</p>
                @endif
            @endforeach
        @endforeach--}}
        <!-- Next Page Link -->
        @if ($paginator->hasMorePages())
            <a onclick="gameComments.loadPage({{ $paginator->currentPage() + 1 }})" href="javascript:void(0)" class="pagination__button hasMorePagesComment isActive"><span class="icon-arrow-right"></span></a>
        @else
            <p class="pagination__button "><span class="icon-arrow-right"></span></p>
        @endif
    </div>
</div>