$(function () {
    initCustomScrollBar();
    openSearchForm();
    closeSearchForm();
    showSearchCleaner();
    clearSearchField();
    openCatalogByHover();
    closeCatalog();
    openGamesBl();
    closeGamesBl();
    toggleGamesBl();
    openMemberForm();
    closeMemberForm();
    toggleMemberForm();
    focusOnField();
    fixLabel();
    showPassword();
    openRecoveryForm();
    closeRecoveryForm();
    backToMemberFrom();
    showThumbPreview();
    drawChart();
    dropLangsList();
    openFeedbackForm();
    closeFeedbackForm();
    openAuthForm();
    openRegisterForm();
    hideHeaderAfterScrollDown();
    openNavbar();
    closeNavbar();
    openCatalogByClick();
    backToNavbar();
    toggleCatalogCol();
    shadowTabs();
    transformGamesCloser();
    stickGamesTabs();
    openBreadcrumbsList();
    openAvatarForm();
    closeAvatarForm();
    showUploadedFileText();
    openPassChangeForm();
    closePassChangeForm();
    initCategoriesSlider();
    openNotWorking();
    closeNotWorking();
    initSliderRangeGames();
    toggleGameBl();
    tabScroll();
    stickBanner();
    // dragBackBtn();
    initProgressBar();
    // openBlogShares();
    initBlogCarousel();
    stickBlogHeadingBtn();
    goBackToBlogTitle();
    scrollDownToBlogFeed();
    initTagsSlider();
    // initRating(); 
  
    gamePop();
  });
    
  function initCustomScrollBar() {
    var $nano = $('.nano');
  
    if (!$nano.length) return;
  
    $nano.nanoScroller();
  
    $('.catalog-row .nano, .catalog-row-single .nano').each(function () {
        var $this = $(this);
  
        $this.on('update', function (e, val) {
            if (val.direction == 'down') {
                $($this).addClass('is-scrolling');
            } else if (val.position == 0) {
                $($this).removeClass('is-scrolling');
            }
  
            if (val.position >= val.maximum - 30) {
                $($this).addClass('is-scrolled');
            } else {
                $($this).removeClass('is-scrolled');
            }
        });
    });
  }
  
  function openSearchForm() {
    
    $('.search-opener').on('click', function (e) {
        e.stopPropagation();
        var $globalParent = $('.wrapper');
        var trigger = 'has-open-search';
  
        if ($('.nav-item').hasClass('is-current')) {
            $('.nav-item').removeClass('is-current');
        }
  
        if ($globalParent.hasClass('has-open-catalog')) {
            $globalParent.removeClass('has-open-catalog');
        }
  
        setTimeout(function () {
            $('.search-field').focus();
        }, 200);
  
        if ($globalParent.hasClass(trigger)) {
            $globalParent.removeClass(trigger);
            $('.search-field').val('').removeClass('is-not-empty not-empty');
        } else {
            $globalParent.addClass(trigger);
        }
  
        $globalParent.on('click', function (e) {
            if (!$(e.target).closest('.search-opener, .search').length) {
                if ($globalParent.hasClass(trigger)) {
                    $globalParent.removeClass(trigger);
                    $('.search-field').val('').removeClass('is-not-empty not-empty');
                }
            }
        });
    });
  }
  
  function closeSearchForm() {
    $('.search-closer').on('click', function () {
        var $globalParent = $('.wrapper');
        var trigger = 'has-open-search';
  
        if ($globalParent.hasClass(trigger)) {
            $globalParent.removeClass(trigger);
            $('.search-field').val('').removeClass('is-not-empty not-empty');
        }
    });
  }
  
  function showSearchCleaner() {
    $('.search-field').on('keyup', function () {
        var val = $(this).val();
        var trigger = 'is-not-empty';
  
        if (val === '') {
            $(this).removeClass(trigger);
        } else {
            $(this).addClass(trigger);
        }
    });
  }
  
  function clearSearchField() {
    $('.search-cleaner').on('click', function () {
        $('.search-field').val('').keyup().focus();
    });
  }
  
  function openCatalogByHover() {
    var menuItemTimer;
    var loaderImg = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: none; display: block; shape-rendering: auto;" width="80px" height="80px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
    <circle cx="50" cy="50" r="45" stroke-width="8" stroke="#e45e14" stroke-dasharray="70.68583470577035 70.68583470577035" fill="none" stroke-linecap="round">
    <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" keyTimes="0;1" values="0 50 50;360 50 50"></animateTransform>
    </circle></svg>`;
  
    $('.nav-link:not(.has-no-catalog)').hover(function () {
        var $globalParent = $('.wrapper');
        var $parent = $(this).parent();
  
        if ($(window).width() > 1024) {
            menuItemTimer = setTimeout(function () {
                $globalParent.addClass('has-open-catalog');
                $('.nav-item.is-current').removeClass('is-current');
                $parent.addClass('is-current');
  
                var url = $parent.closest('.navbar-body').attr("data-get-modal-url");
                var type = $parent.find('.catalog').attr("data-modal-type");
                var localeId = $parent.find('.catalog').attr("data-locale");
                var $btnLine = $parent.find('.btn-line');
  
                if (type == 'girls' || type == 'boys' || type == 'kids') {
                    var pageId = $parent.find('.catalog').attr("data-page");
                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'html',
                        data: { 'type': type, 'locale_id': localeId, 'page_id': pageId, 'items_type': 'genres' },
                        beforeSend: function () {
                            $btnLine.hide();
                            var $appendBox = $parent.find('.catalog-items');
                            $appendBox.append(loaderImg);
                        },
                        success: function success(html) {
                            $parent.find('.catalog-items-genres').html(html);
                            $btnLine.show();
                            initCustomScrollBar();
                            showThumbPreview();
                            lozad('.catalog .demilazyload').observe();
                        },
                        error: function error(xhr, ajaxOptions, thrownError) {
                            console.log('error');
                        }
                    });
  
                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'html',
                        data: { 'type': type, 'locale_id': localeId, 'page_id': pageId, 'items_type': 'heroes' },
                        success: function success(html) {
                            $parent.find('.catalog-items-heroes').html(html);
                            initCustomScrollBar();
                            showThumbPreview();
                            lozad('.catalog .demilazyload').observe();
                        },
                        error: function error(xhr, ajaxOptions, thrownError) {
                            console.log('error');
                        }
                    });
  
                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'html',
                        data: { 'type': type, 'locale_id': localeId, 'page_id': pageId, 'items_type': 'games' },
                        success: function success(html) {
                            $parent.find('.catalog-items-games').html(html);
                            initCustomScrollBar();
                            showThumbPreview()
                            lozad('.catalog .demilazyload').observe();
                        },
                        error: function error(xhr, ajaxOptions, thrownError) {
                            console.log('error');
                        }
                    });
                } else if (type == 'heroes' || type == 'genres') {
                    var catalog = $parent.find('.catalog-items');
  
                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'html',
                        data: { 'type': type, 'locale_id': localeId },
                        beforeSend: function () {
                            $btnLine.hide();
                            var $appendBox = $parent.find('.catalog-items');
                            $appendBox.append(loaderImg);
  
                        },
                        success: function success(html) {
                            catalog.html(html);
                            $btnLine.show();
                            initCustomScrollBar();
                            showThumbPreview();
                            lozad('.catalog .demilazyload').observe();
                        },
                        error: function error(xhr, ajaxOptions, thrownError) {
                            console.log('error');
                        }
                    });
                }
            }, 300)
        } else {
            $globalParent.removeClass('has-open-catalog');
            $parent.removeClass('is-current');
        }
  
        $globalParent.on('click', function (e) {
            if (!$(e.target).closest('.navbar, .catalog').length) {
                $globalParent.removeClass('has-open-catalog');
                $parent.removeClass('is-current');
            }
        });
    }, function () {
        clearTimeout(menuItemTimer);
  
        $('.nav-link:not(.has-no-catalog)').on('click', function() {
            clearTimeout(menuItemTimer);
        })
    })
  }
  
  function openCatalogByClick() {
    var loaderImg = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: none; display: block; shape-rendering: auto;" width="80px" height="80px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
    <circle cx="50" cy="50" r="45" stroke-width="8" stroke="#e45e14" stroke-dasharray="70.68583470577035 70.68583470577035" fill="none" stroke-linecap="round">
    <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" keyTimes="0;1" values="0 50 50;360 50 50"></animateTransform>
    </circle></svg>`;
  
    $('.catalog-opener').on('click', function () {
        var $globalParent = $('.wrapper');
        var $catalog = $(this).siblings('.catalog');
        var $parent = $(this).parent();
  
        var url = $parent.closest('.navbar-body').attr("data-get-modal-url");
        var type = $parent.find('.catalog').attr("data-modal-type");
        var localeId = $parent.find('.catalog').attr("data-locale");
        var $btnLine = $parent.find('.btn-line');
  
        if (type == 'girls' || type == 'boys' || type == 'kids') {
            var pageId = $parent.find('.catalog').attr("data-page");
  
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'html',
                data: { 'type': type, 'locale_id': localeId, 'page_id': pageId, 'items_type': 'genres' },
                beforeSend: function () {
                    var $appendBox = $parent.find('.catalog-items');
                    $appendBox.append(loaderImg)
                },
                success: function success(html) {
                    $parent.find('.catalog-items-genres').html(html);
                    initCustomScrollBar();
                    showThumbPreview()
                    lozad('.catalog .demilazyload').observe();
                },
                error: function error(xhr, ajaxOptions, thrownError) {
                    console.log('error');
                }
            });
  
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'html',
                data: { 'type': type, 'locale_id': localeId, 'page_id': pageId, 'items_type': 'heroes' },
                success: function success(html) {
                    $parent.find('.catalog-items-heroes').html(html);
                    initCustomScrollBar();
                    showThumbPreview()
                    lozad('.catalog .demilazyload').observe();
                },
                error: function error(xhr, ajaxOptions, thrownError) {
                    console.log('error');
                }
            });
  
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'html',
                data: { 'type': type, 'locale_id': localeId, 'page_id': pageId, 'items_type': 'games' },
                success: function success(html) {
                    $parent.find('.catalog-items-games').html(html);
                    initCustomScrollBar();
                    showThumbPreview()
                    lozad('.catalog .demilazyload').observe();
                },
                error: function error(xhr, ajaxOptions, thrownError) {
                    console.log('error');
                }
            });
        } else if (type == 'heroes' || type == 'genres') {
            var catalog = $parent.find('.catalog-items');
  
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'html',
                data: { 'type': type, 'locale_id': localeId },
                beforeSend: function () {
                    $btnLine.hide();
                    var $appendBox = $parent.find('.catalog-items');
                    $appendBox.append(loaderImg);
  
                },
                success: function success(html) {
                    catalog.html(html);
                    $btnLine.show();
                    initCustomScrollBar();
                    showThumbPreview()
                    lozad('.catalog .demilazyload').observe();
                },
                error: function error(xhr, ajaxOptions, thrownError) {
                    console.log('error');
                }
            });
        }
  
        if ($catalog.hasClass('is-open-mob')) {
            $catalog.removeClass('is-open-mob');
        } else {
            $catalog.addClass('is-open-mob');
        }
  
        $globalParent.on('click', function (e) {
            if (!$(e.target).closest('.catalog').length) {
                if (!$globalParent.hasClass('has-open-navbar')) {
                    $('.catalog').removeClass('is-open-mob');
                }
            }
        });
    });
  }
  
  function closeCatalog() {
    $('.catalog-closer').on('click', function () {
        var $globalParent = $('.wrapper');
  
        if ($('.nav-item').hasClass('is-current')) {
            $('.nav-item').removeClass('is-current');
        }
  
        if ($('.catalog').hasClass('is-open')) {
            $('.catalog').removeClass('is-open');
        }
  
        if ($globalParent.hasClass('has-open-catalog')) {
            $globalParent.removeClass('has-open-catalog');
        }
    });
  }
    
  function openGamesBl() {
    $('.js-games-opener').on('click', function () {
        var $target = $('.wrapper');
        var trigger = 'has-open-games';
  
        if ($target.hasClass(trigger)) {
            $target.removeClass(trigger);
        } else {
            $target.addClass(trigger);
        }
  
        $('.games').on('click', function (e) {
            if (!$(e.target).closest('.circle-bg-closer, .games-inner, .scroller-to-top').length) {
                if ($target.hasClass(trigger)) {
                    $target.removeClass(trigger);
                }
            }
        });
    });
  }
    
  function closeGamesBl() {
    $('.circle-bg-closer').on('click', function () {
        var $target = $('.wrapper');
        var trigger = 'has-open-games';
  
        if ($target.hasClass(trigger)) {
            $target.removeClass(trigger);
        }
    });
  }
  
  function toggleGamesBl() {
    $('.js-games-tab').on('click', function (e) {
        var togglerTrigger = 'is-current';
        var targetTrigger = 'is-open';
        e.preventDefault();
  
        var $this = $(this);
        var $data = $this.attr('data-toggler');
        var $target = $('[data-toggle="' + $data + '"]');
  
        $('.js-games-tab.is-current').removeClass(togglerTrigger);
        $(this).addClass(togglerTrigger);
  
        if ($('.games-bl').hasClass(targetTrigger)) {
            $('.games-bl').removeClass(targetTrigger);
        }
  
        $target.addClass(targetTrigger);
    });
  }
  
  function openMemberForm() {
    $('.member-opener, .member-enter').on('click', function () {
        var $target = $('.wrapper');
        var trigger = 'has-open-member';
  
        if ($target.hasClass(trigger)) {
            $target.removeClass(trigger);
        } else {
            $target.addClass(trigger);
        }
  
        $('.member').on('click', function (e) {
            if (!$(e.target).closest('.bg-closer, .member-inner').length) {
                if ($target.hasClass(trigger)) {
                    $target.removeClass(trigger);
                }
            }
        });
    });
  }
  
  function closeMemberForm() {
    $('.bg-closer').on('click', function () {
        var $target = $('.wrapper');
        var trigger = 'has-open-member';
  
        if ($target.hasClass(trigger)) {
            $target.removeClass(trigger);
        }
    });
  }
  
  function toggleMemberForm() {
    $('.js-member-tab').on('click', function (e) {
        e.preventDefault();
  
        var togglerTrigger = 'is-current';
        var targetTrigger = 'is-open';
        var $data = $(this).attr('data-toggler');
        var $target = $('[data-toggle="' + $data + '"]');
  
        $('.js-member-tab.is-current').removeClass(togglerTrigger);
        $(this).addClass(togglerTrigger);
  
        if ($('.member-bl').hasClass(targetTrigger)) {
            $('.member-bl').removeClass(targetTrigger);
        }
  
        $target.addClass(targetTrigger);
    });
  }
  
  function focusOnField() {
    $('.form-field, .form-textarea').on('focus', function () {
        $(this).parent().addClass('is-focused');
    }).on('blur', function (e) {
        $(this).parent().removeClass('is-focused');
    });
  
    if ($('.form-field, .form-textarea').val()) {
        $('.form-field, .form-textarea').addClass('not-empty');
    }
  }
  
  function fixLabel() {
    $('.form-field, .form-textarea').on('keyup', function () {
        var tmpVal = $(this).val();
        var trigger = 'is-not-empty';
  
        if (tmpVal === '') {
            $(this).removeClass(trigger);
            $(this).removeClass('not-empty');
        } else {
            $(this).addClass(trigger);
            $(this).addClass('not-empty');
        }
    });
  }
  
  function showPassword() {
    $('.form-pass-hider').on('click', function () {
        var trigger = 'is-active';
  
        if ($(this).is('.is-active')) {
            $(this).removeClass(trigger).text('Показать');
            $(this).next('input').attr('type', 'password');
        } else {
            $(this).addClass(trigger).text('Скрыть');
            $(this).next('input').attr('type', 'text');
        }
    });
  }
  
  function openRecoveryForm() {
    $('.recovery-opener').on('click', function () {
        var $target = $('.wrapper');
        var triggerFirst = 'has-open-recovery';
        var triggerSecond = 'has-open-member';
  
        if ($target.hasClass(triggerFirst)) {
            $target.removeClass(triggerFirst);
        } else {
            $target.addClass(triggerFirst);
        }
  
        $('.recovery').on('click', function (e) {
            if (!$(e.target).closest('.recovery-back-btn, .bg-closer, .recovery-inner').length) {
                // if ($target.hasClass(triggerFirst)) {
                //   $target.removeClass(triggerFirst)
                // }
  
                // if ($target.hasClass(triggerSecond)) {
                //   $target.removeClass(triggerSecond);
                // }
            }
        });
    });
  }
    
  function closeRecoveryForm() {
    $('.recovery .bg-closer').on('click', function () {
        var $target = $('.wrapper');
        var triggerFirst = 'has-open-recovery';
        var triggerSecond = 'has-open-member';
  
        if ($target.hasClass(triggerFirst) || $target.hasClass(triggerSecond)) {
            $target.removeClass(triggerFirst);
            $target.removeClass(triggerSecond);
        }
    });
  }
  
  function backToMemberFrom() {
    $('.recovery-back-btn').on('click', function () {
        var $target = $('.wrapper');
        var trigger = 'has-open-recovery';
  
        if ($target.hasClass(trigger)) {
            $target.removeClass(trigger);
        }
    });
  }
  
  function closeMailNotify() {
    var $target = $('.wrapper');
    var trigger = 'has-open-mail';
  
    $('.mail .bg-closer').on('click', function () {
        if ($target.hasClass(trigger)) {
            $target.removeClass(trigger);
        }
  
        if ($target.hasClass('has-open-member')) {
            $target.removeClass('has-open-member');
        }
  
        if ($target.hasClass('has-open-recovery')) {
            $target.removeClass('has-open-recovery');
        }
    });
  
    $('.mail').on('click', function (e) {
        if (!$(e.target).closest('.bg-closer, .mail-inner').length) {
            if ($target.hasClass(trigger)) {
                $target.removeClass(trigger);
            }
  
            if ($target.hasClass('has-open-member')) {
                $target.removeClass('has-open-member');
            }
  
            if ($target.hasClass('has-open-recovery')) {
                $target.removeClass('has-open-recovery');
            }
        }
    });
  }
  
  function showThumbPreview() {
    var desk = false;
    var mob = false;
  
    var resizePoint = 1024;
  
    var trigger = '.thumb-inner';
  
    var hoverPing = 'is-hovered';
    var touchPing = 'is-touched';
  
    if (!$(trigger).length) return;
  
    function addVideoSrc($trigger) {
      var place = '.thumb-video';
      var $place = $trigger.find(place);
  
      if (!$place.length) return;
  
      var srcAttr = $place.data('src');
      var template = '<source src="' + srcAttr + '" type="video/mp4">';
      $place.html(template);
  
      var playPromise = $place[0].play();
  
      if (playPromise !== undefined) {
        playPromise.then(function (_) {
  
        }).catch(function (error) {
  
        });
      }
    }
  
    function removeVideoSrc($trigger) {
      var place = '.thumb-video';
      var $place = $trigger.find(place);
  
      var src = 'source';
      var $src = $trigger.find(src);
  
      if (!$src.length) return;
  
      $place[0].pause();
      $src.remove();
    }
  
    function addHoverPing($trigger) {
      $trigger.addClass(hoverPing);
    }
  
    function removeHoverPing($trigger) {
      $trigger.removeClass(hoverPing);
    }
  
    function addTouchPing($trigger) {
      $trigger.addClass(touchPing);
    }
  
    function removeTouchPing($trigger) {
      $trigger.removeClass(touchPing);
    }
  
    function appendCursor($trigger) {
      var cursor = '.cursor';
      var template = '<span class="cursor"></span>';
  
      var place = '.thumb-link';
      var $place = $trigger.find(place);
  
      $place.append(template);
      
      var $cursor = $trigger.find(cursor);
      $cursor.text(mailLang.play)
  
      $cursor.css('top', '-100%');
      $cursor.css('left', '-100%');
  
      moveCursor($trigger, $cursor)
    }
  
    function removeCursor($trigger) {
      var cursor = '.cursor';
      var $cursor = $trigger.find(cursor);
  
      if (!$cursor.length) return;
  
      $cursor.remove();
    }
  
    function moveCursor($trigger, $cursor) {
      $trigger.on('mousemove', function(e) {
        $cursor.css('left', e.originalEvent.offsetX - $cursor.prop('offsetWidth') / 2)
        $cursor.css('top', e.originalEvent.offsetY - $cursor.prop('offsetHeight') / 2)
      })
    }
  
    function addLoader($trigger) {
      var $place = $trigger.find('.img-scaler');
      var $video = $trigger.find('.thumb-video');
  
      var svg = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin: auto; background: none; display: block; shape-rendering: auto;" width="60px" height="60px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">\n' +
      '<circle cx="50" cy="50" r="45" stroke-width="8" stroke="#e45e14" stroke-dasharray="70.68583470577035 70.68583470577035" fill="none" stroke-linecap="round">\n' +
      '<animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" keyTimes="0;1" values="0 50 50;360 50 50"></animateTransform>\n' +
      '</circle></svg>';
  
      var loader = '<div class="thumb-loader">"'+ svg +'"</div>';
  
      $video.on('loadstart', function () {
        var $loader = $place.find('.thumb-loader');
  
        $place.addClass('has-loader');
  
        if ($loader.length) return;
  
        $place.append(loader);
      });
  
      $video.on('canplay', function () {
        var $loader = $place.find('.thumb-loader');
  
        $place.removeClass('has-loader');
  
        $loader.remove();
      });
    }
  
    function removeLoader($trigger) {
      var $place = $trigger.find('.img-scaler');
      var $video = $trigger.find('.thumb-video');
      var $loader = $place.find('.thumb-loader');
  
      $video.off('loadstart');
      $video.off('canplay');
  
      if ($place.hasClass('has-loader')) {
        $place.removeClass('has-loader');
      }
  
      if ($loader.length) {
        $loader.remove();
      };
    }
  
    function bindHoverEvent() {
      var timeout;
  
      $(trigger).on('mouseenter', function (e) {
        var $trigger = $(this);
  
        appendCursor($trigger);
  
        timeout = setTimeout(function () {
          addVideoSrc($trigger);
          addHoverPing($trigger);
          addLoader($trigger);
        }, 200)
      })
  
      $(trigger).on('mouseleave', function (e) {
        var $trigger = $(this);
  
        clearTimeout(timeout);
  
        removeVideoSrc($trigger);
        removeHoverPing($trigger);
        removeCursor($trigger);
        removeLoader($trigger);
      })
    }
  
    function unbindHoverEvent() {
      $(trigger).off('mouseenter');
    }
  
    function bindTouchEvent() {
      var timeout;
  
      $(trigger).on('touchstart', function (e) {
        var $trigger = $(this);
  
        $('.thumb-inner.is-touched').each(function () {
          $(this).removeClass(touchPing);
          $(this).find('.thumb-video')[0].pause();
          $(this).find('source').remove();
        })
  
        $('.wrapper').on('click', function (e) {
          if (!$(e.target).closest('.thumb-inner').length) {
            $('.thumb-inner.is-touched').each(function () {
              $(this).removeClass(touchPing);
              $(this).find('.thumb-video')[0].pause();
              $(this).find('source').remove();
            })
          }
      });
  
        timeout = setTimeout(function () {
          addTouchPing($trigger);
          addVideoSrc($trigger);
          addLoader($trigger);
        }, 100)
      })
  
      $(trigger).on('touchend', function (e) {
        clearTimeout(timeout);
      })
    }
  
    function unbindTouchEvent() {
      $(trigger).off('touchstart');
    }
  
    function detectResize(curWidth) {
      if (curWidth > resizePoint && !desk) {
        desk = true;
        mob = false;
  
        unbindTouchEvent();
        bindHoverEvent();
      }
  
      if (curWidth <= resizePoint && !mob ||
        /Android|webOS|iPhone|iPad|iPod|BlackBerry|BB10|IEMobile|Opera Mini/.test(navigator.userAgent)
      ) {
        desk = false;
        mob = true;
  
        bindTouchEvent();
        unbindHoverEvent();
      }
    }
  
    var curWidth = $(window).innerWidth();
  
    detectResize(curWidth);
  
    $(window).on('resize', function () {
      var curWidth = $(window).innerWidth();
  
      detectResize(curWidth);
    })
  }
  
  function drawChart() {
    var circles = document.querySelectorAll('.label-progress-percents');
  
    if (!circles.length) return;
  
    circles.forEach(function (el) {
        var percent = el.dataset.percent / 100;
        var diameter = el.offsetWidth;
        var circumference = Math.ceil(diameter * Math.PI);
        var stroke = Math.ceil(circumference * percent);
        var diff = circumference - stroke;
        el.querySelector('.label-progress-inner').style.strokeDasharray = stroke + 'px ' + diff + 'px';
    });
  }
  
  function dropLangsList() {
    $('.langs-opener').on('click', function () {
        var $globalParent = $('.wrapper');
        var $this = $(this);
        var $parent = $this.parent();
        var trigger = 'is-open';
  
        if ($parent.hasClass(trigger)) {
            $parent.removeClass(trigger);
        } else {
            $parent.addClass(trigger);
        }
  
        $globalParent.on('click', function (e) {
            if (!$(e.target).closest('.langs').length) {
                if ($parent.hasClass(trigger)) {
                    $parent.removeClass(trigger);
                }
            }
        });
    });
  }
  
  function openFeedbackForm() {
    $('.feedback-opener, a[href="#feedback"], .mail-link').on('click', function () {
        var $target = $('.wrapper');
        var trigger = 'has-open-feedback';
  
        if ($target.hasClass('has-open-not-working')) {
            $target.removeClass(trigger);
        }
  
        if ($target.hasClass(trigger)) {
            $target.removeClass(trigger);
        } else {
            $target.addClass(trigger);
        }
  
        $('.feedback').on('click', function (e) {
            if (!$(e.target).closest('.bg-closer, .feedback-inner').length) {
                if ($target.hasClass('has-open-not-working')) {
                    $target.removeClass(trigger);
                }
  
                if ($target.hasClass('has-open-mail')) {
                    $target.removeClass(trigger);
                }
  
                if ($target.hasClass(trigger)) {
                    $target.removeClass(trigger);
                }
            }
        });
    });
  }
  
  function closeFeedbackForm() {
    $('.feedback .bg-closer').on('click', function () {
        var $target = $('.wrapper');
        var trigger = 'has-open-feedback';
  
        if ($target.hasClass(trigger)) {
            $target.removeClass(trigger);
        }
    });
  }
  
  function openAuthForm() {
    $('.auth-opener').on('click', function () {
        var $target = $('.wrapper');
        var trigger = 'has-open-member';
  
        var $authTab = $('[data-toggler="' + 'auth' + '"]');
        var $registerTab = $('[data-toggler="' + 'register' + '"]');
  
        var $authForm = $('[data-toggle="' + 'auth' + '"]');
        var $registerForm = $('[data-toggle="' + 'register' + '"]');
  
        $authTab.addClass('is-current');
        $authForm.addClass('is-open');
        $registerTab.removeClass('is-current');
        $registerForm.removeClass('is-open');
  
        if ($target.hasClass(trigger)) {
            $target.removeClass(trigger);
        } else {
            $target.addClass(trigger);
        }
  
        $('.member').on('click', function (e) {
            if (!$(e.target).closest('.bg-closer, .member-inner').length) {
                if ($target.hasClass(trigger)) {
                    $target.removeClass(trigger);
                }
            }
        });
    });
  }
  
  function openRegisterForm() {
    $('.register-opener').on('click', function () {
        var $target = $('.wrapper');
        var trigger = 'has-open-member';
  
        var $authTab = $('[data-toggler="' + 'auth' + '"]');
        var $registerTab = $('[data-toggler="' + 'register' + '"]');
  
        var $authForm = $('[data-toggle="' + 'auth' + '"]');
        var $registerForm = $('[data-toggle="' + 'register' + '"]');
  
        $authTab.removeClass('is-current');
        $authForm.removeClass('is-open');
        $registerTab.addClass('is-current');
        $registerForm.addClass('is-open');
  
        if ($target.hasClass(trigger)) {
            $target.removeClass(trigger);
        } else {
            $target.addClass(trigger);
        }
  
        $('.member').on('click', function (e) {
            if (!$(e.target).closest('.bg-closer, .member-inner').length) {
                if ($target.hasClass(trigger)) {
                    $target.removeClass(trigger);
                }
            }
        });
    });
  }
  
  function hideHeaderAfterScrollDown() {
    var docEl = document.documentElement;
    var header = document.querySelector('header');
    var main = document.querySelector('main');
    var fixTrigger = 'is-hide';
    var prevScroll = window.scrollY || docEl.scrollTop;
    var curScroll;
    var dir = 0;
    var prevDir = 0;
    var headerHeight = 104;
  
    if (header.querySelector('.mail-confirm')) {
        header.classList.add('has-notify');
    }
  
    function toggleHeader(dir, curScroll) {
        if (dir === 'down' && curScroll > headerHeight) {
            header.classList.add(fixTrigger);
            prevDir = dir;
        } else if (dir === 'up') {
            header.classList.remove(fixTrigger);
            prevDir = dir;
        }
    };
  
    function checkScroll() {
        curScroll = window.scrollY || docEl.scrollTop;
  
        if (curScroll > prevScroll) {
            dir = 'down';
        } else if (curScroll < prevScroll) {
            dir = 'up';
        }
  
        if (dir !== prevDir) {
            toggleHeader(dir, curScroll);
        }
  
        prevScroll = curScroll;
    };
  
    window.addEventListener('scroll', checkScroll);
  }
  
  function openNavbar() {
    $('.burger').on('click', function () {
        var $globalParent = $('.wrapper');
        var trigger = 'has-open-navbar';
  
        if ($globalParent.hasClass(trigger)) {
            $globalParent.removeClass(trigger);
        } else {
            $globalParent.addClass(trigger);
        }
  
        $globalParent.on('click', function (e) {
            if (!$(e.target).closest('.burger, .navbar, .navbar-closer, .catalog, .member, .games').length) {
                if ($globalParent.hasClass(trigger)) {
                    $globalParent.removeClass(trigger);
                }
            }
        });
    });
  }
  
  function closeNavbar() {
    $('.navbar-closer').on('click', function () {
        var $globalParent = $('.wrapper');
  
        if ($globalParent.hasClass('has-open-navbar')) {
            $globalParent.removeClass('has-open-navbar');
        }
    });
  }
  
  function backToNavbar() {
    $('.catalog-back-btn').on('click', function () {
        var $target = $('.catalog');
        var $trigger = 'is-open-mob';
  
        if ($target.hasClass($trigger)) {
            $target.removeClass($trigger);
        }
    });
  }
  
  function toggleCatalogCol() {
    $('.js-catalog-tab').click(function (e) {
        e.preventDefault();
  
        var togglerTrigger = 'is-current';
        var targetTrigger = 'is-open';
        var $data = $(this).attr('data-toggler');
        var $target = $('[data-toggle="' + $data + '"]');
  
        $('.js-catalog-tab.is-current').removeClass(togglerTrigger);
        $(this).addClass(togglerTrigger);
  
        $('.js-catalog-col.is-open').removeClass(targetTrigger);
        $target.addClass(targetTrigger);
    });
  }
  
  function shadowTabs() {
    var $nano = $('.js-nano');
  
    if (!$nano.length) return;
  
    $nano.each(function () {
        $(this).on('update', function (e, val) {
            if (val.direction == 'down') {
                $(this).addClass('is-scrolling');
            } else if (val.position == 0) {
                $(this).removeClass('is-scrolling');
            }
        });
    });
  }
  
  function transformGamesCloser() {
    $('.games').on('scroll', function () {
        if ($(this).scrollTop() > 0) {
            $('.circle-bg-closer').addClass('is-transformed');
        } else {
            $('.circle-bg-closer').removeClass('is-transformed');
        }
    });
  }
  
  function stickGamesTabs() {
    $('.games').on('scroll', function () {
        if ($(this).scrollTop() >= $('.games .tabs').offset().top) {
            $(".games .tabs-wrap").addClass('is-fixed');
        } else {
            $(".games .tabs-wrap").removeClass('is-fixed');
        }
    });
  }
  
  function openBreadcrumbsList() {
    $('.breadcrumbs-opener').on('click', function () {
        var $target = $('.wrapper');
        var trigger = 'has-open-breadcrumbs';
  
        if ($target.hasClass(trigger)) {
            $target.removeClass(trigger);
        } else {
            $target.addClass(trigger);
        }
  
        $target.on('click', function (e) {
            if (!$(e.target).closest('.breadcrumbs-opener, .breadcrumbs').length) {
                if ($target.hasClass(trigger)) {
                    $target.removeClass(trigger);
                }
            }
        });
    });
  }
  
  function openAvatarForm() {
    $('.js-avatar-opener, .profile-user-changer').on('click', function () {
        var $target = $('.wrapper');
        var trigger = 'has-open-avatar';
  
        if ($target.hasClass(trigger)) {
            $target.removeClass(trigger);
        } else {
            $target.addClass(trigger);
        }
  
        $('.avatar').on('click', function (e) {
            if (!$(e.target).closest('.bg-closer, .avatar-inner').length) {
                if ($target.hasClass(trigger)) {
                    $target.removeClass(trigger);
                }
            }
        });
    });
  }
  
  function closeAvatarForm() {
    $('.bg-closer').on('click', function () {
        var $target = $('.wrapper');
        var trigger = 'has-open-avatar';
  
        if ($target.hasClass(trigger)) {
            $target.removeClass(trigger);
        }
    });
  }
  
  function showUploadedFileText() {
    $(".uploader-field").change(function (e) {
        var _URL = window.URL || window.webkitURL;
        var file, img;
        if (file = this.files[0]) {
            img = new Image();
            img.onload = function () {
                if ((this.width || this.height) < 180) {
                    $(".uploader-field").value = '';
                    $('.uploader-field').parent('.uploader').removeClass('is-loaded');
                } else {
                    $('.uploader-field').parent('.uploader').addClass('is-loaded').find('.uploader-file').text(file.name);
                }
            };
            img.src = _URL.createObjectURL(file);
        } else {
            $('.uploader-field').parent('.uploader').removeClass('is-loaded');
        }
    });
  }
  
  function openPassChangeForm() {
    $('.js-pass-changer').on('click', function () {
        var $target = $('.wrapper');
        var trigger = 'has-open-pass-change';
  
        if ($target.hasClass(trigger)) {
            $target.removeClass(trigger);
        } else {
            $target.addClass(trigger);
        }
  
        $('.pass-change').on('click', function (e) {
            if (!$(e.target).closest('.bg-closer, .pass-change-inner').length) {
                if ($target.hasClass(trigger)) {
                    $target.removeClass(trigger);
                }
            }
        });
    });
  }
  
  function closePassChangeForm() {
    $('.bg-closer').on('click', function () {
        var $target = $('.wrapper');
        var trigger = 'has-open-pass-change';
  
        if ($target.hasClass(trigger)) {
            $target.removeClass(trigger);
        }
    });
  }
  
  function initCategoriesSlider() {
    var $categoriesSwiper = $('.categories-swiper-container');
  
    if (!$categoriesSwiper.length) return;
  
    var maxResize = false;
    var minResize = false;
    var swiper = '';
  
    function detectResize(curWidth) {
        var $holder = $('.categories-swiper-container');
        var $firstHolderChild = $('.categories-swiper-wrapper');
        var $secondHolderChild = $('.categories-swiper-slide');
  
        if (curWidth <= targetWidth && !minResize) {
            maxResize = false;
            minResize = true;
  
            $holder.addClass('swiper-container');
            $firstHolderChild.addClass('swiper-wrapper');
            $secondHolderChild.addClass('swiper-slide');
  
            if ($secondHolderChild.length >= 8) {
                $firstHolderChild.removeClass('nowrap');
                swiper = new Swiper('.categories-swiper-container', {
                    slidesPerView: "auto",
                    freeMode: true,
                    slidesPerColumn: 2
  
                });
            } else {
                $firstHolderChild.addClass('nowrap');
                swiper = new Swiper('.categories-swiper-container', {
                    slidesPerView: "auto",
                    freeMode: true
                });
            }
        } else if (curWidth >= targetWidth && !maxResize) {
            maxResize = true;
            minResize = false;
  
            $holder.removeClass('swiper-container');
            $firstHolderChild.removeClass('swiper-wrapper');
            $secondHolderChild.removeClass('swiper-slide');
  
            if (typeof swiper === "object" ) {
                swiper.destroy();
            }
        }
    }
  
    var targetWidth = 1139;
    var curWidth = window.innerWidth;
  
    detectResize(curWidth);
  
    window.addEventListener('resize', function () {
        var curWidth = window.innerWidth;
  
        detectResize(curWidth);
    });
  }
  
  function openNotWorking() {
    $('.js-not-working').on('click', function () {
        var $target = $('.wrapper');
        var trigger = 'has-open-not-working';
  
        if ($target.hasClass(trigger)) {
            $target.removeClass(trigger);
        } else {
            $target.addClass(trigger);
        }
  
        $('.not-working').on('click', function (e) {
            if (!$(e.target).closest('.bg-closer, .not-working-inner').length) {
                if ($target.hasClass(trigger)) {
                    $target.removeClass(trigger);
                }
            }
        });
    });
  }
  
  function closeNotWorking() {
    $('.bg-closer').on('click', function () {
        var $target = $('.wrapper');
        var trigger = 'has-open-not-working';
  
        if ($target.hasClass(trigger)) {
            $target.removeClass(trigger);
        }
    });
  }
  
  function initSliderRangeGames() {
    var $gameSwiper = $('.game-swiper-container');
  
    if (!$gameSwiper.length) return;
  
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|BB10|IEMobile|Opera Mini/.test(navigator.userAgent)) {
      var swiper = new Swiper('.game-swiper-container', {
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev'
        },
        freeMode: true,
        spaceBetween: 20,
        slidesPerView: 'auto',
      });
    } else {
      var swiper = new Swiper('.game-swiper-container', {
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
        },
        breakpoints: {
          320: {
              freeMode: true,
              spaceBetween: 20,
              slidesPerView: 2.2,
          },
          1024: {
              freeMode: false,
              spaceBetween: 28,
              slidesPerView: 5,
              slidesPerGroup: 4,
              centeredSlides: true,
              centeredSlidesBounds: true
          }
        }
      });
    }
  }
  
  function toggleGameBl() {
    var $target = $('.game-toggle-bl');
    var $feedback = $('[data-toggle="' + 'feedback' + '"]');
    $feedback.hide();
  
    $('.game-tab').on('click', function (e) {
        var toggler = $(this).data('toggler');
        var togglerTrigger = 'is-current';
  
        var hiddenOnRight = $('.game-tab:not(.is-current)').offset().left + $('.game-tab:not(.is-current)').width() > $(window).width();
        var hiddenOnLeft = $('.game-tab:not(.is-current)').offset().left < 0;
        var wrap = $('.game-tab:not(.is-current)').closest('.game-tab');
  
        if (hiddenOnRight) {
            wrap.animate({ scrollLeft: $('.game-tab:not(.is-current)').closest('.game-tab').scrollLeft() + $('.game-tab:not(.is-current)').offset().left - 10 }, 200);
        } else if (hiddenOnLeft) {
            wrap.animate({ scrollLeft: $('.game-tab:not(.is-current)').closest('.game-tab').scrollLeft() + $('.game-tab:not(.is-current)').offset().left - 10 }, 200);
        }
  
        $('.game-tab.is-current').removeClass(togglerTrigger);
        $(this).addClass(togglerTrigger);
  
        $target.hide().filter(function (e) {
            return $(this).data('toggle') === toggler;
        }).show();
  
        $('.game-toggle-zone').hide();
  
        $('.headline, .about-headline').hide();
    });
  
    $(".game-tab-all").click(function (e) {
        $target.show();
        $feedback.hide();
        if ($(window).width() > 1024) {
            $('.game-toggle-zone').show();
        }
        $('.headline, .about-headline').show();
    });
  
    $(".game-review-link, .js-go-feed").click(function (e) {
        $([document.documentElement, document.body]).animate({
            scrollTop: $('.game-tabs-container').offset().top
        }, 300);
        $target.hide();
        $('.game-toggle-zone').hide();
        $('.game-tab.is-current').removeClass('is-current');
        $('[data-toggler="' + 'feedback' + '"]').addClass('is-current');
        $feedback.show();
    });
  }
  
  function tabScroll() {
    $('.game-tab').on('click', function () {
        var hiddenOnRight = $(this).offset().left + $(this).width() > $(window).width();
        var hiddenOnLeft = $(this).offset().left < 0;
        var wrap = $(this).closest('.game-tabs');
        if (hiddenOnRight) {
            wrap.animate({ scrollLeft: $(this).closest('.game-tabs').scrollLeft() + $(this).offset().left - 10 }, 200);
        } else if (hiddenOnLeft) {
            wrap.animate({ scrollLeft: $(this).closest('.game-tabs').scrollLeft() + $(this).offset().left - 10 }, 200);
        }
    });
  }
  
  function stickBanner() {
    var stickyBl = $('.sticky-bl');
  
    if (!stickyBl.length) return;
  
    $('.sticky-bl').sticky({ topSpacing: 0, responsiveWidth: false });
    $(document).on('scroll', function () {
        if ($(this).scrollTop() >= $lastEl.offset().top) {
            $('.sticky-bl').addClass('is-stopped');
            $('.sticky-bl').unstick();
        } else {
            $('.sticky-bl').removeClass('is-stopped');
            $('.sticky-bl').sticky({ topSpacing: 10 });
        }
    });
  }
  
  // function dragBackBtn() {
  //   $('body').on('touchmove', '.back-btn', function (e) {
  //       var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
  
  //       if ($(this).parents('.mob-game').hasClass('is-horizontal')) {
  //         if (touch.pageX > 20 && touch.pageX + 60 < $(window).width()) {
  //           $('.back-btn').css('left', Math.round(touch.pageX));
  //         }
  //         // if ($(window).innerWidth() > $(window).innerHeight()) {
  //         //   if (touch.pageY > 20 && touch.pageY + 60 < $(window).height()) {
  //         //     $('.back-btn').css('top', Math.round(touch.pageY));
  //         //   }
  //         // } else {
  //         //   if (touch.pageX > 20 && touch.pageX + 60 < $(window).width()) {
  //         //     $('.back-btn').css('left', Math.round(touch.pageX));
  //         //   }
  //         // }
  //       } else {
  //         if (touch.pageY > 20 && touch.pageY + 60 < $(window).height()) {
  //           $('.back-btn').css('top', Math.round(touch.pageY));
  //         }
  //       }
  //   });
  // }
  
  function initProgressBar() {
    var $progressBar = $('.progressbar');
  
    if (!$progressBar.length) return;
  
    function funcInner() {
        var scroll = document.body.scrollTop || document.documentElement.scrollTop;
        var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        var scrolled = scroll / height * 100;
  
        document.querySelector('.progressbar').style.width = scrolled + '%';
    }
  
    if (document.contains(document.querySelector('.progressbar'))) {
        window.addEventListener('scroll', funcInner);
    }
  }
    
  // function openBlogShares() {
  //   var hovered;
  
  //   $('.author-shares').hover(
  //     function() {
  //       hovered = setTimeout(function () {
  //         $('.author-shares').addClass('is-open');
  //       }, 500)
  //     },
  //     function() {
  //       clearTimeout(hovered);
  //       hovered = setTimeout(function () {
  //         $('.author-shares').removeClass('is-open');
  //       }, 500)
  //     }
  //   )
  // }
  
  function initBlogCarousel() {
    var $carousel = $('.carousel');
  
    if (!$carousel.length) return;
  
    var swiper = new Swiper('.carousel', {
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
        },
        mousewheel: false,
        slidesPerView: 1
    });
  }
  
  function stickBlogHeadingBtn() {
    var $stickyBtn = $('.sticky-btn');
  
    if (!$stickyBtn.length) return;
  
    var maxResize = false;
    var minResize = false;
  
    function detectResize(curWidth) {
        if (curWidth < targetWidth && !minResize) {
            maxResize = false;
            minResize = true;
  
            $stickyBtn.unstick();
  
            $(window).on('scroll', function () {
                if ($(this).scrollTop() > 300) {
                    $stickyBtn.addClass('shown');
                } else {
                    $stickyBtn.removeClass('shown');
                }
            });
        } else if (curWidth >= targetWidth && !maxResize) {
            maxResize = true;
            minResize = false;
  
            $stickyBtn.sticky({ topSpacing: 70, responsiveWidth: false });
        }
    }
  
    var targetWidth = 1320;
    var curWidth = $(window).innerWidth();
  
    if ($target.length) {
        detectResize(curWidth);
  
        $(window).on('resize', function () {
            var curWidth = $(window).innerWidth();
  
            detectResize(curWidth);
        });
    }
  }
  
  function goBackToBlogTitle() {
    $(".sticky-btn").click(function (e) {
        console.log('test');
        $([document.documentElement, document.body]).animate({
            scrollTop: $('main').offset().top
        }, 300);
    });
  }
  
  function scrollDownToBlogFeed() {
    $(".comments-redirect").click(function (e) {
        $([document.documentElement, document.body]).animate({
            scrollTop: $('.blog-feed').offset().top
        }, 300);
    });
  }
  
  function initTagsSlider() {
    var $tags = $('.tags');
  
    if (!$tags.length) return;
  
    var maxResize = false;
    var minResize = false;
    var swiper = '';
  
    function detectResize(curWidth) {
      var $holder = $('.tags-nav');
      var $firstHolderChild = $('.tags-list');
      var $secondHolderChild = $('.tags-item');
  
      if (curWidth <= targetWidth && !minResize) {
        maxResize = false;
        minResize = true;
  
        $holder.addClass('swiper-container');
        $firstHolderChild.addClass('swiper-wrapper');
        $secondHolderChild.addClass('swiper-slide');
  
        swiper = new Swiper('.tags-nav', {
          slidesPerView: "auto",
          freeMode: true,
          slidesPerColumn: 2
        });
      } else if (curWidth >= targetWidth && !maxResize) {
        maxResize = true;
        minResize = false;
  
        $holder.removeClass('swiper-container');
        $firstHolderChild.removeClass('swiper-wrapper');
        $secondHolderChild.removeClass('swiper-slide');
  
        if (typeof swiper === "object" ) {
          swiper.destroy();
        }
      }
    }
  
    var targetWidth = 1024;
    var curWidth = window.innerWidth;
  
    detectResize(curWidth);
  
    window.addEventListener('resize', function () {
      var curWidth = window.innerWidth;
  
      detectResize(curWidth);
    });
  }
  
  function gamePop() {
    $('.js-refresh-iframe').on('click', function () {
      var wrapper = $('.game-frame-box');
      var iframe = wrapper.find('iframe').clone();
  
      wrapper.find('iframe').remove();
      wrapper.append(iframe);
    });
  
    $('.js-go-fullscreen').on('click', function () {
      turnOnFullscreen();
    });
  
    $('body').on('click', '.js-game-back', function (e) {
      if (document.exitFullscreen) {
        document.exitFullscreen();
      } else if (document.webkitExitFullscreen) {
        document.webkitExitFullscreen();
      } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
      } else if (document.msExitFullscreen) {
        document.msExitFullscreen();
      }
  
      $('.mob-game').hide();
  
      if ($('html').hasClass('is-locked')) {
        $('html').removeClass('is-locked');
      } else if ($('html').hasClass('is-horizontal-locked')) {
        $('html').removeClass('is-horizontal-locked');
      }
    });
  
    function popupCenter(config) {
      var width = config.width;
      var height = config.height;
      var src = config.src;
      var alt = config.alt;
      var styles = config.styles;

      var dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
      var dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;
  
      var w = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
      var h = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
  
      var systemZoom = width / window.screen.availWidth;
      var left = (w - width) / 2 / systemZoom + dualScreenLeft;
      var top = (h - height) / 2 / systemZoom + dualScreenTop;
  
      var newWindow = window.open(src, alt, '\n      scrollbars=yes,\n      width=' + w / systemZoom + ',\n      height=' + h / systemZoom + ',\n      top=' + top + ',\n      left=' + left + '\n    ');

      // here we need to edit margins of created window to support offsets

      if (window.focus) newWindow.focus();
    }
  
    function turnOnFullscreen() {
      var devices = /Android|webOS|iPhone|iPad|iPod|BlackBerry|BB10|IEMobile|Opera Mini/.test(navigator.userAgent);
  
      var el;
  
      if ($(window).width() < 1025 || devices) {
        el = document.querySelector('.mob-game');
      } else {
        el = document.querySelector('.game-frame-box iframe');
      }
  
      var requestMethod = el.requestFullScreen || el.webkitRequestFullScreen || el.mozRequestFullScreen || el.msRequestFullScreen;
      
      if (requestMethod) {
        requestMethod.call(el);
      }
    }
  
    function resizing() {
      function detectResize(winWidth, winHeight) {
        if (winWidth > winHeight) {
          $('.mob-game').removeClass('is-vertical');
          $('.mob-game').addClass('is-horizontal');
  
          $('body').off('touchmove', '.mob-game.has-horizontal-iframe .back-btn');
          $('.mob-game.has-horizontal-iframe .back-btn').css('left', '');
          $('body').on('touchmove', '.mob-game.has-horizontal-iframe .back-btn', function (e) {
            var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
  
            if (touch.pageY > 20 && touch.pageY + 60 < $(window).height()) {
              $('.back-btn').css('top', Math.round(touch.pageY));
            }
          })
  
          if ($('.mob-game').hasClass('required-rotate')) {
            $('.mob-game').removeClass('required-rotate');
          }
        }
  
        if (winWidth < winHeight) {
          $('.mob-game').removeClass('is-horizontal');
          $('.mob-game').addClass('is-vertical');
  
          $('body').off('touchmove', '.mob-game.has-horizontal-iframe .back-btn');
          $('.mob-game.has-horizontal-iframe .back-btn').css('top', '');
          $('body').on('touchmove', '.mob-game.has-horizontal-iframe .back-btn', function (e) {
            var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
  
            if (touch.pageX > 20 && touch.pageX + 60 < $(window).width()) {
              $('.back-btn').css('left', Math.round(touch.pageX));
            }
          })
  
          if (!$('.mob-game').hasClass('required-rotate')) {
            $('.mob-game').addClass('required-rotate');
          }
        }
      }
  
      var winWidth = $(window).innerWidth();
      var winHeight = $(window).innerHeight();
  
      detectResize(winWidth, winHeight);
    
      $(window).on('resize', function () {
        var winWidth = $(window).innerWidth();
        var winHeight = $(window).innerHeight();
    
        detectResize(winWidth, winHeight);
      })
    }
  
    $('.js-start-game').on('click', function () {
      var $trigger = $(this);
      $('.iframe-buttons').show();
  
      var width = $trigger.data('iframe-width');
      var height = $trigger.data('iframe-height');
      var src = $trigger.data('iframe-src');
      var styles = $trigger.data('iframe-styles');
      var alt = $trigger.attr('alt');
      var sandbox = $trigger.data('sandbox') == 1 ? ' sandbox="allow-same-origin allow-scripts allow-modals" ' : '';
      var fullscreen = $trigger.data('fullscreen');
      var targetBlank = $trigger.data('target-blank');
      var horizontal = $trigger.data('horizontal');
  
      var devices = /Android|webOS|iPhone|iPad|iPod|BlackBerry|BB10|IEMobile|Opera Mini/.test(navigator.userAgent);
  
      if (targetBlank == 1) {
        popupCenter({ 
          width: width, 
          height: height,
          src: src, 
          alt: alt,
          styles: styles,
        });
        return;
      }

      if (($(window).width() < 1025) || devices) {
        if ($('.mob-game').length) {
          $('.mob-game').show();
          $('.back-btn').show();
        } else {
          var frame = "<div class='mob-game'><div class=\"back-btn js-game-back\"><i class=\"icon-arrow-left\"></i></div><iframe " + sandbox + " src='" + src + "' width='100%' height='100%' marginwidth='0' marginheight='0' frameborder='0' allow='autoplay;fullscreen' class='mob-game-frame' allowfullscreen></iframe></div></div>";
          $('body').append(frame);
          $trigger.text(mailLang.сontinue_game);
        }
  
        if (horizontal == 1) {
          $('.mob-game').addClass('has-horizontal-iframe');
          resizing();
          $('html').addClass('is-horizontal-locked');
  
          turnOnFullscreen();
  
          if (screen.orientation) {
            screen.orientation.lock('landscape');
          } else if (screen.msLockOrientation) {
            screen.msLockOrientation.lock('landscape');
          } else if (screen.mozLockOrientation) {
            screen.mozLockOrientation.lock('landscape');
          } else {
            $('.mob-game').removeClass('has-horizontal-iframe');
            $('.mob-game').addClass('has-default-iframe');
            $('.mob-game').addClass('required-rotate');
  
            if ($('.mob-game').hasClass('has-default-iframe')) {
              $('body').on('touchmove', '.back-btn', function (e) {
                var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
    
                if (touch.pageY > 20 && touch.pageY + 60 < $(window).height()) {
                  $('.back-btn').css('top', Math.round(touch.pageY));
                }
              })
            }
          }
        } else{
          $('.mob-game').addClass('has-default-iframe');
  
          if ($('.mob-game').hasClass('has-default-iframe')) {
            $('body').on('touchmove', '.back-btn', function (e) {
              var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
    
              if (touch.pageY > 20 && touch.pageY + 60 < $(window).height()) {
                $('.back-btn').css('top', Math.round(touch.pageY));
              }
            })
          }
          $('html').addClass('is-locked');
          turnOnFullscreen();
        }
      } else {


        var frame = "<iframe " + sandbox + " src='" + src + "' width='" + width + "' height='" + height + "' marginwidth='0' marginheight='0' frameborder='0' id='gameIframe' allow='autoplay;fullscreen' allowfullscreen style='" + styles + "'></iframe>";
        var frameBox = $('.game-frame-box');
  
        if (fullscreen) {
          frameBox.addClass('canFullscreen');
        }
  
        if (width == '100%') {
          frameBox.attr('style', 'min-width: 100%; overflow: hidden');
        }
  
        frameBox.show();
        frameBox.append(frame);
  
        $trigger.closest('.game-frame').addClass('is-loaded');
        $('.game-frame-inner').hide();
      }
    })
  }
  
  var mobile = false;
  if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|BB10|IEMobile|Opera Mini/.test(navigator.userAgent)) {
    mobile = true;
    $('html').addClass('mobile');
  }
  
  function isIE8orlower() {
    var c = "0";
    var d = getInternetExplorerVersion();
    if (d > -1) {
        if (d >= 9) {
            c = 0;
        } else {
            c = 1;
        }
    }
    return c;
  };
  
  function getInternetExplorerVersion() {
    var f = -1;
    if (navigator.appName == "Microsoft Internet Explorer") {
        var e = navigator.userAgent;
        var d = new RegExp("MSIE ([0-9]{1,}[.0-9]{0,})");
        if (d.exec(e) != null) {
            f = parseFloat(RegExp.$1);
        }
    }
    return f;
  }
  
  function showAlert(txt, ok) {
    var sounddiv = 'smallbox';
    if (ok) sounddiv = 'messagebox';
  
    if (isIE8orlower() == 0) {
        var A = document.createElement("audio");
        if (navigator.userAgent.match("Firefox/")) {
            A.setAttribute("src", "/static/sound/" + sounddiv + ".ogg");
        } else {
            A.setAttribute("src", "/static/sound/" + sounddiv + ".mp3");
        }
        //b.get();
        A.addEventListener("load", function () {
            A.play();
        }, true);
        A.pause();
        A.play();
    }
  
    setTimeout(function () {
        if (ok) {
            $('.success-bl').css({ 'opacity': '1', 'visibility': 'visible', 'z-index': '1000' });
            $('.info-bl-text').html(txt);
        } else {
            $('.error-bl').css({ 'opacity': '1', 'visibility': 'visible', 'z-index': '1000' });
            $('.info-bl-text').html(txt);
        }
    }, 300);
    setTimeout(function () {
        $('.success-bl').css({ 'opacity': '0', 'visibility': 'hidden' });
        $('.error-bl').css({ 'opacity': '0', 'visibility': 'hidden' });
    }, 5000);
  }
  
  $(document).ready(function () {
    $('.js-fav').on('click', function (e) {
        e.preventDefault();
        if ($(this).hasClass('isActive')) {
            $(this).find('i').text('В избранное');
            if ($(document).width() > 991) {
                showAlert('Удалено из избранного', false);
            } else {
                showAlert('Игра удалена из избранного', false);
            }
            $(this).removeClass('isActive');
        } else {
            $(this).find('i').text('В избранном');
            if ($(document).width() > 991) {
                showAlert('Добавлено в избранное', true);
            } else {
                showAlert('Игра добавлена в избранное', true);
            }
            $(this).addClass('isActive');
        }
    });
  
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
            return "<span class='searchitem__item'>" + (suggestion.data.image ? "<img class='searchitem__img' src='" + suggestion.data.image + "'>" : '') + "<span class='searchitem__name'>" + suggestion.value.replace(new RegExp(pattern, 'gi'), '<strong>$1<\/strong>') + "</span><span class='searchitem__info'>" + suggestion.data.description + "</span></span>";
        },
        beforeRender: function (container, suggestions) {
            $('.js-search-top').hide();
            $('.requests-title').hide();
            if (suggestions.length > 2) {
                $(container).find('.autocomplete-suggestion').last().after('<button type="button" onclick="$(\'.search-submitter\').click();" class="searchitem__all button">' + mailLang.show_all + '</button>');
            }
        },
        onHide: function () {
            $('.js-search-top').show();
            $('.requests-title').show();
        }
    });
  
    $('body').on('click', '.js-search-top', function (e) {
        $('.js-search-input').val($(this).find('span').text()).addClass('not-empty').addClass('is-not-empty').autocomplete('onValueChange');
    }); 
  
    $('.wrapper').click(function (e) {
        if (!$(e.target).closest('.autocomplete-suggestions').length) {
            $('.autocomplete-suggestions').hide();
            $('.js-search-top').show();
            $('.requests-title').show();
        }
    });
  
    $('.search-closer').on('click', function () {
        $('.autocomplete-suggestions').hide();
        $('.js-search-top').show();
        $('.requests-title').show();
    });
  
    $(document).on("click", "button[type=submit], input[type=submit]", function (e) {
        $(this).closest('form').find('[required]:visible').addClass('required');
    });
  
    $(document).on('submit', 'form[data-success-alert]', function (e) {
        if ($(this).data('check-passwords') === true) {
            if ($(this).find('[name=newpassword]').val() !== $(this).find('[name=repeatpassword]').val()) {
                $(this).find('[name=repeatpassword]').val('');
                return false;
            }
        }
        var txt = $(this).data('success-alert');
        showAlert(txt, true);
        e.preventDefault();
    });
  
    $(document).on('click', '.js-alert-close', function (e) {
        e.preventDefault();
        $(this).closest('.alert').removeClass('isActive');
        setTimeout(function () {
            $(this).closest('.alert').remove();
        }, 200);
    });
  
    $(document).on('submit', 'form[data-success-text]', function (e) {
        if ($(this).data('check-passwords') === true) {
            if ($(this).find('[name=newpassword]').val() !== $(this).find('[name=repeatpassword]').val()) {
                $(this).find('[name=repeatpassword]').val('');
                return false;
            }
        }
        // AJAX FUNCTION TO SEND DATA MUST BE HERE
        // $.fancybox.close();
        var txt = $(this).data('success-text');
        showAlert(txt, true);
        e.preventDefault();
    });
  
    $('input[required], text[required]').on('keyup', function () {
        tmpval = $(this).val();
        if (tmpval === '') {
            $(this).removeClass('not-empty');
        } else {
            $(this).addClass('not-empty');
        }
    });
  
    document.addEventListener('invalid', function () {
        return function (e) {
            e.preventDefault();
        };
    }(), true);
  
    $(".show-more-btn").on('click', function () { 
      $(this).parents('.categories-second').addClass('isVisible');
      $(this).parents('.categories-swiper-container').addClass('isVisible');
      $(this).parents('.categories-carousel').addClass('isVisible');
      $(this).parents('.categories').addClass('isVisible');
      $(this).parents('.has-btn').hide();
    });
  
    $('.info-bl-closer').on('click', function () {
        $('.success-bl').css({'opacity': '0', 'visibility': 'hidden'});
        $('.error-bl').css({'opacity': '0', 'visibility': 'hidden'});
    })
  });
  