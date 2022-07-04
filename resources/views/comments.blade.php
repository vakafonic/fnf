<?php

?>
<div class="feed-row">
    <div class="feed-col-first">
        <a class="userpic">
            @if (Auth::guest())
                <span class="user-letter">{{ mb_substr($lang['guest'], 0, 1) }}</span>
            @else
                @if(Auth::user()->isAvatar())
                    <img class="userpic-img"
                         src="{{ Auth::user()->small_avatar }}?time={{ strtotime(Auth::user()->update_at) }}"
                         alt="placeholder">
                @else
                    <span class="user-letter">{{ mb_substr(Auth::user()->username, 0, 1) }}</span>
                @endif
            @endif
        </a>
    </div>
    <div class="feed-col-middle">
        <div class="feed-textarea-wrap">
                                    <textarea class="feed-textarea"
                                              placeholder="{{ $lang['tell_us_your_impression_game'] }}"
                                              id="textareaAdd"></textarea>
        </div>
        <div class="primary-btn-wrap">
            <button class="primary-btn" type="submit" onclick="gameComments.add();">
                <span class="primary-btn-text">{{ $lang['commenting'] }}</span>
            </button>
        </div>
    </div>
</div>
<div class="comments">
    @if($comments->count() > 0)
        @foreach ($comments as $comment)
            <div class="comment" itemprop="review" itemscope itemtype="http://schema.org/Review">
                @if(!empty($game_name))
                    <meta itemprop="itemReviewed" content="{{ $game_name }}"> @endif
                <div class="comment-col">
                    <a class="userpic">
                        @if(!empty($comment->user))
                            @if($comment->user->isAvatar())
                                <img class="userpic-img"
                                     src="{{ $comment->user->small_avatar }}?time={{ strtotime($comment->user->update_at) }}"
                                     alt="placeholder">
                            @else
                                <span class="user-letter">{{ mb_substr($comment->user->username, 0, 1) }}</span>
                            @endif
                        @else
                            <span class="user-letter">{{ mb_substr($lang['guest'], 0, 1) }}</span>
                        @endif
                    </a>
                </div>
                <div class="comment-col-last">
                    <div class="comment-headline" itemprop="author" itemscope itemtype="https://schema.org/Person">
                        <div class="comment-name" itemprop="name">
                            @if(!empty($comment->user))
                                {{ $comment->user->username }}
                            @else{{ $lang['guest'] }}@endif
                        </div>
                        <div class="comment-date">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $comment->created_at)->setTimezone('Europe/Kiev')->format('d.m.Y H:i') }}</div>
                        @if($comment->isChange() == true)
                            {{--                                    <button class="comment-changer" type="button" onclick="gameComments.change({{ $comment->id }})">--}}
                            {{--                                        <span class="comment-changer-text">{{ $lang['edit_comment'] }}</span>--}}
                            {{--                                    </button>--}}
                        @endif
                    </div>
                    <p class="comment-text" itemprop="reviewBody">
                        {!! nl2br($comment->text) !!}
                    </p>
                    <div class="comment-rate">
                        @if($comment->isCheckUser() == false)
                            @if($comment_rate = $comment->getCheckUserRate())
                                <button class="comment-like" disabled type="button"
                                        onclick="gameComments.rate({{ $comment->id }},1)">
                                    <i class="icon-like"></i>
                                </button>
                                <button class="comment-dislike" type="button" disabled
                                        onclick="gameComments.rate({{ $comment->id }},0)">
                                    <i class="icon-dislike"></i>
                                </button>
                            @else
                                <button class="comment-like" type="button"
                                        onclick="gameComments.rate({{ $comment->id }},1)">
                                    <i class="icon-like"></i>
                                </button>
                                <button class="comment-dislike" type="button"
                                        onclick="gameComments.rate({{ $comment->id }},0)">
                                    <i class="icon-dislike"></i>
                                </button>
                            @endif
                        @endif
                        @if ($com_sum = $comment->rate_up - $comment->rate_down) @endif
                        <span class="comment-{{$com_sum < 0 ? 'negative' : 'positive' }}">{{ $com_sum == 0 ? '' : ($com_sum > 0 ? "+" . $com_sum : $com_sum) }}</span>
                    </div>
                </div>
                <meta itemprop="datePublished"
                      content="{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $comment->created_at)->setTimezone('Europe/Kiev')->format('Y-m-d') }}"/>
            </div>
        @endforeach
{{--    @else--}}
{{--        <p class="text-center search__title">{{ $lang['no_comment'] }}</p>--}}
    @endif
</div>
@if(!empty($full))
    @if($comments->currentPage() != $comments->lastPage() && $comments->total() > 0)
        <button onclick="gameComments.loadMore()" type="button" data-page="{{ $comments->currentPage() }}"
                class="primary-btn loadmore_comments">
            {{ $lang['show_more'] }} {{ $count_next_page }} {{ plural($count_next_page, $lang['plural_review'], $current_locale->id ?? 1) }}
        </button>
    @endif
@endif