/*! lozad.js - v1.14.0 - 2019-10-31
* https://github.com/ApoorvSaxena/lozad.js
* Copyright (c) 2019 Apoorv Saxena; Licensed MIT */


(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
    typeof define === 'function' && define.amd ? define(factory) :
    (global.lozad = factory());
  }(this, (function () { 'use strict';
  
    /**
     * Detect IE browser
     * @const {boolean}
     * @private
     */
    var isIE = typeof document !== 'undefined' && document.documentMode;
  
    var defaultConfig = {
      rootMargin: '0px',
      threshold: 0,
      load: function load(element) {
        if (element.nodeName.toLowerCase() === 'picture') {
          var img = document.createElement('img');
          if (isIE && element.getAttribute('data-iesrc')) {
            img.src = element.getAttribute('data-iesrc');
          }
  
          if (element.getAttribute('data-alt')) {
            img.alt = element.getAttribute('data-alt');
          }
  
          element.append(img);
        }
  
        if (element.nodeName.toLowerCase() === 'video' && !element.getAttribute('data-src')) {
          if (element.children) {
            var childs = element.children;
            var childSrc = void 0;
            for (var i = 0; i <= childs.length - 1; i++) {
              childSrc = childs[i].getAttribute('data-src');
              if (childSrc) {
                childs[i].src = childSrc;
              }
            }
  
            element.load();
          }
        }
  
        if (element.getAttribute('data-poster')) {
          element.poster = element.getAttribute('data-poster');
        }
  
        if (element.getAttribute('data-src')) {
          element.src = element.getAttribute('data-src');
        }
  
        if (element.getAttribute('data-srcset')) {
          element.setAttribute('srcset', element.getAttribute('data-srcset'));
        }
  
        if (element.getAttribute('data-background-image')) {
          element.style.backgroundImage = 'url(\'' + element.getAttribute('data-background-image').split(',').join('\'),url(\'') + '\')';
        } else if (element.getAttribute('data-background-image-set')) {
          var imageSetLinks = element.getAttribute('data-background-image-set').split(',');
          var firstUrlLink = imageSetLinks[0].substr(0, imageSetLinks[0].indexOf(' ')) || imageSetLinks[0]; // Substring before ... 1x
          firstUrlLink = firstUrlLink.indexOf('url(') === -1 ? 'url(' + firstUrlLink + ')' : firstUrlLink;
          if (imageSetLinks.length === 1) {
            element.style.backgroundImage = firstUrlLink;
          } else {
            element.setAttribute('style', (element.getAttribute('style') || '') + ('background-image: ' + firstUrlLink + '; background-image: -webkit-image-set(' + imageSetLinks + '); background-image: image-set(' + imageSetLinks + ')'));
          }
        }
  
        if (element.getAttribute('data-toggle-class')) {
          element.classList.toggle(element.getAttribute('data-toggle-class'));
        }
      },
      loaded: function loaded() {}
    };
  
    function markAsLoaded(element) {
      element.setAttribute('data-loaded', true);
    }
  
    var isLoaded = function isLoaded(element) {
      return element.getAttribute('data-loaded') === 'true';
    };
  
    var onIntersection = function onIntersection(load, loaded) {
      return function (entries, observer) {
        entries.forEach(function (entry) {
          if (entry.intersectionRatio > 0 || entry.isIntersecting) {
            observer.unobserve(entry.target);
  
            if (!isLoaded(entry.target)) {
              load(entry.target);
              markAsLoaded(entry.target);
              loaded(entry.target);
            }
          }
        });
      };
    };
  
    var getElements = function getElements(selector) {
      var root = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : document;
  
      if (selector instanceof Element) {
        return [selector];
      }
  
      if (selector instanceof NodeList) {
        return selector;
      }
  
      return root.querySelectorAll(selector);
    };
  
    function lozad () {
      var selector = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '.lozad';
      var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
  
      var _Object$assign = Object.assign({}, defaultConfig, options),
          root = _Object$assign.root,
          rootMargin = _Object$assign.rootMargin,
          threshold = _Object$assign.threshold,
          load = _Object$assign.load,
          loaded = _Object$assign.loaded;
  
      var observer = void 0;
  
      if (typeof window !== 'undefined' && window.IntersectionObserver) {
        observer = new IntersectionObserver(onIntersection(load, loaded), {
          root: root,
          rootMargin: rootMargin,
          threshold: threshold
        });
      }
  
      return {
        observe: function observe() {
          var elements = getElements(selector, root);
  
          for (var i = 0; i < elements.length; i++) {
            if (isLoaded(elements[i])) {
              continue;
            }
  
            if (observer) {
              observer.observe(elements[i]);
              continue;
            }
  
            load(elements[i]);
            markAsLoaded(elements[i]);
            loaded(elements[i]);
          }
        },
        triggerLoad: function triggerLoad(element) {
          if (isLoaded(element)) {
            return;
          }
  
          load(element);
          markAsLoaded(element);
          loaded(element);
        },
  
        observer: observer
      };
    }
  
    return lozad;
  
  })));

//* ==================================================

(function($) {
    "use strict";
    $(".mobile-toggle").click(function(){
        $(".nav-menus").toggleClass("open");
    });
    $(".mobile-toggle-left").click(function(){
        $(".nav-menus-left").toggleClass("open");
    });
    $(".mobile-search").click(function(){
        $(".search-full").addClass("open");
    });
    $(".close-search").click(function(){
        $(".search-full").removeClass("open");
    });
    $(".bookmark-search").click(function(){
        $(".form-control-search").toggleClass("open");
    })
    $(".filter-toggle").click(function(){
        $(".product-sidebar").toggleClass("open");
    });
    $(".toggle-data").click(function(){
        $(".product-wrapper").toggleClass("sidebaron");
    });
    $(".form-control-search").keyup(function(e){
        if(e.target.value) {
            $(".page-wrapper").addClass("offcanvas-bookmark");
        } else {
            $(".page-wrapper").removeClass("offcanvas-bookmark");
        }
    });
    initLazyLoad();
    showThumbPreview();
})(jQuery);

$('.loader-wrapper').fadeOut('slow', function() {
    $(this).remove();
});

$(window).on('scroll', function() {
    if ($(this).scrollTop() > 600) {
        $('.tap-top').fadeIn();
    } else {
        $('.tap-top').fadeOut();
    }
});
$('.tap-top').click( function() {
    $("html, body").animate({
        scrollTop: 0
    }, 600);
    return false;
});

function toggleFullScreen() {
    if ((document.fullScreenElement && document.fullScreenElement !== null) ||
        (!document.mozFullScreen && !document.webkitIsFullScreen)) {
        if (document.documentElement.requestFullScreen) {
            document.documentElement.requestFullScreen();
        } else if (document.documentElement.mozRequestFullScreen) {
            document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullScreen) {
            document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
        }
    } else {
        if (document.cancelFullScreen) {
            document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        }
    }
}
(function($, window, document, undefined) {
    "use strict";
    var $ripple = $(".js-ripple");
    $ripple.on("click.ui.ripple", function(e) {
        var $this = $(this);
        var $offset = $this.parent().offset();
        var $circle = $this.find(".c-ripple__circle");
        var x = e.pageX - $offset.left;
        var y = e.pageY - $offset.top;
        $circle.css({
            top: y + "px",
            left: x + "px"
        });
        $this.addClass("is-active");
    });
    $ripple.on(
        "animationend webkitAnimationEnd oanimationend MSAnimationEnd",
        function(e) {
            $(this).removeClass("is-active");
        });
})(jQuery, window, document);

$(".chat-menu-icons .toogle-bar").click(function(){
    $(".chat-menu").toggleClass("show");
});

// active link
$('.footer.footer-fix').parents('.page-body-wrapper').addClass('hasfooterfix'); 
// new WOW().init();

//* 2020-08-06 ==================================================

function initLazyLoad() {
    var observer = lozad('.demilazyload');

    observer.observe();
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
      $cursor.text('Играть')
  
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
  
    function bindHoverEvent() {
      var timeout;
  
      $(trigger).on('mouseenter', function (e) {
        var $trigger = $(this);
  
        appendCursor($trigger);
  
        timeout = setTimeout(function () {
          addVideoSrc($trigger);
          addHoverPing($trigger);
        }, 100)
      })
  
      $(trigger).on('mouseleave', function (e) {
        var $trigger = $(this);
  
        clearTimeout(timeout);
  
        removeVideoSrc($trigger);
        removeHoverPing($trigger);
        removeCursor($trigger);
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
  
        $('.page-wrapper').on('click', function (e) {
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
