$(window).load(function() {

    // $(".show-more-btn").on('click', function (e) {
    //     alert('wooocki');
    //     $('categories').addClass('isVisible');
    //     $('categories-second').addClass('isVisible');
    // });
    $('.js-search-input').autocomplete({
        serviceUrl: mailLang.route_search_ajax,
        type: 'POST',
        dataType: 'json',
        minChars: 3,
        showNoSuggestionNotice: true,
        noSuggestionNotice: mailLang.noSuggestionNotice,
        //lookup: suggestions,
        onSelect: function (suggestion) {
            window.location.href = suggestion.data.url;
        },
        formatResult: function (suggestion, currentValue) {
            var reEscape = new RegExp('(\\' + ['/', '.', '*', '+', '?', '|', '(', ')', '[', ']', '{', '}', '\\'].join('|\\') + ')', 'g');
            var pattern = '(' + currentValue.replace(reEscape, '\\$1') + ')';
            return "<span class='searchitem__item'>" + (suggestion.data.image ? "<img class='searchitem__img' src='" + suggestion.data.image + "'>" : '') +
                "<span class='searchitem__name'>" + suggestion.value.replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>') +
                "</span><span class='searchitem__info'>" + suggestion.data.description + "</span></span>"
                ;
        },
        beforeRender: function (container, suggestions) {
            $('.js-search-top').addClass('isHidden');
            if (suggestions.length > 2) {
                $(container).find('.autocomplete-suggestion').last().after('<button type="button" onclick="$(\'.header__search--submit\').click();" class="searchitem__all button">' + mailLang.show_all + '</button>')
            }
        },
        onHide: function () {
            $('.js-search-top').removeClass('isHidden');
        }
    });

});

console.log(1);

