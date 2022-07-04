<div class="rating" data-id="{{ $page->id }}" data-url="{{ $url }}" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
    <meta itemprop="ratingCount" content="{{ $page->getPageRatingCount() }}">
    <meta itemprop="itemReviewed" content="{{$page->getName($current_locale->id)}}">
    <div class="rating-inner">
        <div class="rating-avg-indicator" data-avg-grade="{{ $page->rating }}" data-user-grade="{{\App\Http\Controllers\RatingController::getUserRating($url, $page->id)}}"></div>
        <div class="rating-grades">
            <input id="rating-grade-5" class="rating-grade" type="radio" name="rating" value="5">
            <label for="rating-grade-5" class="rating-label"></label>
            <input id="rating-grade-4" class="rating-grade" type="radio" name="rating" value="4">
            <label for="rating-grade-4" class="rating-label"></label>
            <input id="rating-grade-3" class="rating-grade" type="radio" name="rating" value="3">
            <label for="rating-grade-3" class="rating-label"></label>
            <input id="rating-grade-2" class="rating-grade" type="radio" name="rating" value="2">
            <label for="rating-grade-2" class="rating-label"></label>
            <input id="rating-grade-1" class="rating-grade" type="radio" name="rating" value="1">
            <label for="rating-grade-1" class="rating-label"></label>
        </div>
        <span class="rating-avg-val" itemprop="ratingValue">{{ $page->rating }}</span>
    </div>
</div>
