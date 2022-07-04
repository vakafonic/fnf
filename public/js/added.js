$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {
    $('.header__search-input').autocomplete({
        serviceUrl: mailLang.route_search_ajax,
        maxHeight: 500,
        type: 'POST',
        dataType: 'json',
        minChars: 3,
        showNoSuggestionNotice: true,
        appendTo: '.search-result__body',
        noSuggestionNotice: mailLang.noSuggestionNotice,
        onSelect: function (suggestion) {
            window.location.href = suggestion.data.url;
        },
        formatResult: function (suggestion, currentValue) {
            var reEscape = new RegExp('(\\' + ['/', '.', '*', '+', '?', '|', '(', ')', '[', ']', '{', '}', '\\'].join('|\\') + ')', 'g');
            var pattern = '(' + currentValue.replace(reEscape, '\\$1') + ')';
            return '<a class="search-result__item" href="' + suggestion.data.url + '"><span class="search-result__item-img"><picture><source srcset="' + suggestion.data.image + '" type="image/webp"><img alt="#" src="' + suggestion.data.image + '"></picture></span><span class="search-result__item-desc"><span class="search-result__item-name">'+ suggestion.value.replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>') +'</span></span></a>';
        },
        beforeRender: function (container, suggestions) {
            if (suggestions.length == 3) {
                $(container).find('.autocomplete-suggestion').last().after('<div class="show-all-button-div"><a class="button" onclick="$(\'.header__form-search-btn_submit\').click();">' + mailLang.show_all + '</a></div>');
            }
        },
        onHide: function () {
            $('.js-search-top').show();
            $('.requests-title').show();
        }
    });
});