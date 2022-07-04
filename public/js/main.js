var mobile = false;
if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|BB10|IEMobile|Opera Mini/.test(navigator.userAgent)) {
	mobile = true;
	$('html').addClass('mobile');
}

function drawCharts() {
	var circles = document.querySelectorAll('.percent-circle');
	circles.forEach(function (el) {
		var percent = el.dataset.percent / 100;
		var diameter = el.offsetWidth;
		var circumference = Math.ceil(diameter * Math.PI);
		var stroke = Math.ceil(circumference * percent);
		var diff = circumference - stroke;
		el.querySelector('.percent-circle-inner').style.strokeDasharray = stroke + 'px ' + diff + 'px';
	});
}

function isIE8orlower() {
	var c = "0";
	var d = getInternetExplorerVersion();
	if (d > -1) {
		if (d >= 9) {
			c = 0
		} else {
			c = 1
		}
	}
	return c
};

function getInternetExplorerVersion() {
	var f = -1;
	if (navigator.appName == "Microsoft Internet Explorer") {
		var e = navigator.userAgent;
		var d = new RegExp("MSIE ([0-9]{1,}[.0-9]{0,})");
		if (d.exec(e) != null) {
			f = parseFloat(RegExp.$1)
		}
	}
	return f
}

function showAlert(txt, ok) {
	var sounddiv = 'smallbox';
	if (ok) sounddiv = 'messagebox';

	if (isIE8orlower() == 0) {
		var A = document.createElement("audio");
		if (navigator.userAgent.match("Firefox/")) {
			A.setAttribute("src", "/static/sound/" + sounddiv + ".ogg")
		} else {
			A.setAttribute("src", "/static/sound/" + sounddiv + ".mp3")
		}
		//b.get();
		A.addEventListener("load", function () {
			A.play()
		}, true);
		A.pause();
		A.play()
	}

	setTimeout(function () {
		if (ok) {
			$('.success-bl').css({'opacity': '1','visibility': 'visible','z-index': '1000'});
			$('.info-bl-text').html(txt);
		}else{
			$('.error-bl').css({'opacity': '1','visibility': 'visible','z-index': '1000'});
			$('.info-bl-text').html(txt);
		}
	}, 300);
	setTimeout(function () {
		$('.success-bl').css({'opacity': '0','visibility': 'hidden'});
		$('.error-bl').css({'opacity': '0','visibility': 'hidden'});
	}, 5000);
}

function itemWrapMouseenter() {
	if ($(document).width() > 991) {
		$('.item__wrap').mouseenter(function () {

			// check and add video with atribute
			if (!$(this).find('video')[0].length) {
				var thisVideo = $(this).find('video');
				var videoSrc = thisVideo.data('src');
				if (videoSrc.length > 5) {
					thisVideo.html('<source src="' + videoSrc + '" type="video/mp4">');
				}
			}

			$(this).find('video')[0].play();
			var cursor = document.createElement('span');
			cursor.classList.add('item__play');
			cursor.innerHTML = mailLang.play; //'Играть';
			$(this).find('.item__link').append(cursor);
			$(this).find('.item__play').css('opacity', 1);
			this.addEventListener('mousemove', function gameMouseMove(v) {
				cursor.style.left = v.offsetX - (cursor.offsetWidth / 2) + 'px';
				cursor.style.top = v.offsetY - (cursor.offsetHeight / 2) + 'px';
				this.addEventListener('mouseleave', function () {
					this.removeEventListener('mousemove', gameMouseMove);
					this.style.cursor = 'pointer'
				})
			});
			this.style.cursor = 'none';
		});
		$('.item__wrap').mouseleave(function () {
			if ($(this).find('.item__play')) {
				$(this).find('.item__play').remove();
			}
		});
		$('.item').mouseleave(function () {
			$(this).find('video')[0].pause();
		});

		// const observer = lozad(); // lazy loads elements with default selector as '.lozad'
		// observer.observe();
	}
}

function initDropdownMenuArrows() {
	$('.nav__dropdown').each(function (i, el) {
		const link = $(el).closest('.header__menu--item');
		const offset = link.offset().left;
		const linkWidth = link.outerWidth() / 2;
		const menuOffset = $(this).offset().left;
		const left = (offset + linkWidth) - menuOffset;
		$(this).find('.nav__arrow').css('left', left);
	});
}

function checkCatsNameWidth() {
	$('.cat__name span').each(function (i, el) {
		$(this).removeClass('isOneline');
		var lineHeight = parseInt($(this).css('line-height').replace('px', ''));
		if ($(this).innerHeight() <= lineHeight) {
			$(this).addClass('isOneline');
		}
	});
}
function watchCatsLoaded() {
	if ($('.cats').length) {
		var interval = setInterval(function () {
			if ($('.cats .cat').length) {
				checkCatsNameWidth();
				clearInterval(interval);
			}
		}, 500);
	}
}

function checkIfBackButtonVisible() {
	if ($('.back-btn').length) {
		var top = $('.back-btn').offset().top;
		var height = $('.back-btn').height();
		var windowHeight = $(window).height();
		if ((top - height) > windowHeight) {
			$('.back-btn').css('top', '70px');
		}
	}
}

$(window).on('resize', function () {
	initDropdownMenuArrows();
	checkCatsNameWidth();
	checkIfBackButtonVisible();
});

$(document).ready(function () {
	watchCatsLoaded();

	initDropdownMenuArrows();

	$('.js-start-game').on('click', function () {
		if($('.adblocker-notify').length) {
			$('.adblocker-notify').show();
		}
	});

	$(window).click(function () {
		$('.langs').removeClass('isActive');
	});
	$('.langs').on('click', function (e) {
		e.stopPropagation();
	});
	$('.langs__current').on('click', function (e) {
		$(this).parent('.langs').toggleClass('isActive');
	});
	$('.langs').hover(
		function () { },
		function () {
			$('.langs').removeClass('isActive');
		}
	);

	$('.js-load-all-viewed').on('click', function () {
		$.fancybox.open({
			src: '#mygames',
			opts: {
				buttons: ['close'],
				touch: false,
				baseClass: 'fancybox-games fancybox-popup',
				afterShow: function (instance, current) {
					$('.fancybox-slide').scroll(function () {
						if ($(this).scrollTop() > 150) {
							$('.fancybox-close-small:visible').addClass('isActive');
							$(this).find('.totop').addClass('isActive');
						} else {
							$('.fancybox-close-small:visible').removeClass('isActive');
							$(this).find('.totop').removeClass('isActive');
						}
					});
					$('#mygames:visible .popup__tabs .popup__tabs--link:last-child').trigger('click');
				}
			}
		});
	});

	$('.header__mmenu').on('click', '.header__menu--label:not(.isActive)', function (e) {
		var twoplaterslink = $(this).parent().is('.url-two-players') && $(window).width() < 991;
		if (!twoplaterslink) {
			if ($(this).next('.nav__dropdown').length > 0) {
				e.preventDefault();
				$(this).addClass('isActive').next('.nav__dropdown').addClass('isActive');
				return false;
			}
		}
	});
	$('.header__mmenu').on('click', '.header__menu--label.isActive', function (e) {
		e.preventDefault();
		$(this).removeClass('isActive');
		$('.nav__dropdown.isActive').removeClass('isActive');
		return false;
	});

	$('.js-hamburger').on('click', function (e) {
		e.preventDefault();
		$('html').addClass('isLock');
		$('.header__mmenu').addClass('isActive');
	});

	$('.js-close-mmenu').on('click', function (e) {
		e.preventDefault();
		$('html').removeClass('isLock');
		$('.header__mmenu').removeClass('isActive');
	});

	$('.header__menu').clone().appendTo('.header__mmenu');

	var menuItemTimer;
	$('.header__menu--item:not(.isSingle):not(.isVisible)').hover(
		function () {
			// lozad('.menu_lozad').observe();
			var that = this;
			menuItemTimer = setTimeout(function () {
				$('.header__menu--item.isVisible').removeClass('isVisible');
				$(that).addClass('isVisible')
				$('html').addClass('isMenuHovered');
			}, 500);
		},
		function () {
			clearTimeout(menuItemTimer);
		});

	$('body').on('click', function (e) {
		if (!$(e.target).closest('.nav__dropdown').length) {
			$('.header__menu--item.isVisible').removeClass('isVisible');
			$('html').removeClass('isMenuHovered');
		}
	});

	var hovered = false;
	$('.js-hover-delayed').hover(
		function () {
			$(this).addClass('isActive');
			hovered = true;
		},
		function () {
			var that = this;
			hovered = false;
			setTimeout(function () {
				if (!hovered) {
					$(that).removeClass('isActive');
				}
			}, 300);
		});

	$('.js-show-reviews').on('click', function (e) {
		e.preventDefault();
		$([document.documentElement, document.body]).animate({
			scrollTop: $('.tabs').offset().top
		}, 500);
		$('.tabs__caption--button:nth-child(2)').trigger('click');
		return false;
	});

	// $('.js-emoji').emojioneArea({
	// 	search: false,
	// 	hidePickerOnBlur: true
	// });

	$('.js-refresh-iframe').on('click', function () {
		const wrapper = $(this).closest('div');
		const iframe = wrapper.find('iframe').clone();
		wrapper.find('iframe').remove();
		wrapper.append(iframe);
	});

	$('.js-go-fullscreen').on('click', function () {
		turnOnFullscreen();
	});
	function turnOnFullscreen() {
		var el;
		if ($(window).width() < 992) {
			el = document.querySelector('.mob-game');
		} else {
			el = document.querySelector('.game-frame-box iframe');
		}
		var requestMethod = el.requestFullScreen || el.webkitRequestFullScreen || el.mozRequestFullScreen || el.msRequestFullScreen;
		if (requestMethod) {
			requestMethod.call(el);
		}
	}

	var movingBackButton = false;
	$('body').on('touchstart', '.js-game-back', function (e) {
		movingBackButton = true;
	});
	$('body').on('touchmove', '.js-game-back', function (e) {
		if (movingBackButton) {
			var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
			if (touch.pageY > 40 && touch.pageY + 40 < $(window).height()) {
				$(this).css('top', Math.round(touch.pageY));
			}
		}
	});
	$('body').on('touchend', '.js-game-back', function (e) {
		movingBackButton = false;
	});
	$('body').on('click', '.js-game-back', function (e) {
		if (document.exitFullscreen) {
			document.exitFullscreen();
		}
		$('.mob-game').hide();
		$('html').removeClass('isLock');
		return false;
	});

	const popupCenter = ({ url, title, w, h }) => {
		// Fixes dual-screen position                             Most browsers      Firefox
		const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
		const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;

		const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
		const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

		const systemZoom = width / window.screen.availWidth;
		const left = (width - w) / 2 / systemZoom + dualScreenLeft
		const top = (height - h) / 2 / systemZoom + dualScreenTop
		const newWindow = window.open(url, title,
			`
      scrollbars=yes,
      width=${w / systemZoom},
      height=${h / systemZoom},
      top=${top},
      left=${left}
      `
		)

		if (window.focus) newWindow.focus();
	}

	$('.js-start-game').on('click', function () {
		var url = $(this).data('iframe-src');
		var width = $(this).data('iframe-width');
		var height = $(this).data('iframe-height');
		var title = $(this).attr('alt');

		var blankNew = $(this).data('target-blank');
		if (blankNew == 1) {
			/*windowHeight = window.innerHeight ? window.innerHeight : $(window).height();
			windowWidth = window.innerWidth ? window.innerWidth : $(window).width();
			console.log(windowWidth, windowHeight)
			console.log(width, height)
			var right = (windowWidth/2)-(width/2);
			var top = (windowHeight/2)-(height/2) - 50;

			console.log(right, top)
			window.open(url,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=no,width=' + width + ',height=' + height +', top='+top+', right='+right);*/
			popupCenter({ url: url, title: title, w: width, h: height });
			return true;
		}

		var sandbox = $(this).data('sandbox') == 1 ? ' sandbox="allow-same-origin allow-scripts allow-modals" ' : '';
		if ($(window).width() < 992) {
			if ($('.mob-game-frame').length > 0) {
				$('.mob-game').show();
				$('.js-game-back').show();
			} else {
            var frame = "<div class='mob-game'><div class=\"back-btn js-game-back\"><i class=\"icon-arrow-left\"></i></div><iframe " + sandbox + " src='" + url + "' width='100%' height='100%' marginwidth='0' marginheight='0' frameborder='0' allow='autoplay;fullscreen' class='mob-game-frame' allowfullscreen></iframe></div></div>";
				$('body').append(frame);
				$(this).text(mailLang.сontinue_game);
			}
			turnOnFullscreen();
			$('html').addClass('isLock');
		} else {
			var fullscreen = $(this).data('fullscreen');
			var frame = "<iframe " + sandbox + " src='" + url + "' width='" + width + "' height='" + height + "' marginwidth='0' marginheight='0' frameborder='0' id='gameIframe' allow='autoplay;fullscreen' allowfullscreen></iframe>";
			var play = $('.game-frame-box');
			if (fullscreen) play.addClass('canFullscreen');
            play.show();
			play.append(frame);
			play.addClass('isGame');
			if (width == '100%') {
				play.attr('style', 'min-width: 100%');
			}
			$(this).closest('.game-frame').addClass('isLoaded');
			$('.game-frame-inner').hide();
			if ($(window).width() < width) {
				play.addClass('isFull');
				$('html').addClass('isLock');
				play.append('<button class="game__frame--back js-back-game" type="button"></button>');

				var $draggable = $('.js-back-game').draggabilly({
					axis: 'y',
					containment: '.game__frame--play'
				});
				$draggable.on('staticClick', function (event, pointer) {
					$(this).closest('.game-frame').removeClass('isLoaded');
					$(this).closest('.game-frame-box').removeClass('isGame canFullscreen isFull').find('iframe').remove();
					$('html').removeClass('isLock');
					$('.js-back-game').remove();
				});
			}

		}
	});

	// var $dragging = null;
	// $('.game__frame').on('mousedown', '.js-back-game', function (e) {
	// 	$dragging = $(e.target);
	// });
	// $(document).on('mouseup', function () {
	// 	console.log(1)
	// 	$dragging = null;
	// });
	// $('.game__frame').on('mouseleave', function () {
	// 	$dragging = null;
	// });
	// $('.game__frame').on('mousemove', function(e) {
	// 	if ($dragging) {
	// 		$dragging.offset({
	// 			top: e.pageY
	// 		});
	// 	}
	// });
	//
	// $('.game__frame--play').on('click', '.js-back-game', function(){
	//
	// });

	$(".js-avatar-upload").change(function (e) {
		var _URL = window.URL || window.webkitURL;
		var file, img;
		if ((file = this.files[0])) {
			img = new Image();
			img.onload = function () {
				if ((this.width || this.height) < 180) {
					showAlert('Файл слишком маленький');
					$(".js-avatar-upload").value = '';
					$('.js-avatar-upload').parent('.upload__button').removeClass('isLoaded');
				} else {
					$('.js-avatar-upload').parent('.upload__button').addClass('isLoaded').find('.upload__button--file').text(file.name);
				}
			};
			img.src = _URL.createObjectURL(file);
		} else {
			$('.js-avatar-upload').parent('.upload__button').removeClass('isLoaded');
		}
	});


	$('.js-show-cats').on('click', function (e) {
		e.preventDefault();
		$(this).closest('.cats').addClass('isVisible');
		if ($('.search').length > 0) {
			$('.cats').prev('.title').find('.title__h3 i').text($('.cats > div:not(.cats__all)').length);
		}
	});


	$('.nav__scroller').each(function (i, el) {
		if (el.scrollHeight > $(el).innerHeight()) $(el).addClass('isScroll');
	});

	$('.nav__scroller').scroll(function (e) {
		if ($(this).scrollTop() > 15) {
			$(this).addClass('isScrolled');
		} else {
			$(this).removeClass('isScrolled');
		}
		if ($(this)[0].scrollHeight - $(this).scrollTop() === $(this).outerHeight()) {
			$(this).addClass('isEnd');
		} else {
			$(this).removeClass('isEnd');
		}
	});


	//$('.item:even').addClass('isVisited');

	drawCharts();

	$('.mygames').on('click', '.popup__tabs--link:not(.isActive)', function () {
		$(this).addClass('isActive').siblings().removeClass('isActive')
			.closest('div.mygames').find('div.mygames__content').removeClass('isActive').hide().eq($(this).index()).addClass('isActive').show();
		drawCharts();
	});

	$('.path__back').on('click', function () {
		history.back();
	});

	$('.js-mobcats').on('click', '.mobcats__link.isActive', function (e) {
		$(this).closest('.js-mobcats').toggleClass('isActive');
		e.preventDefault();
		return false;
	});

	if ($(document).width() < 992) {
		$('.path__down').on('click', function (e) {
			$(this).closest('.path').toggleClass('isActive');
		});
		$('.underpath').on('click', function (e) {
			if ($('.path.isActive').length > 0) {
				e.preventDefault();
				$('.path.isActive').removeClass('isActive');
				return false;
			}
		});
	};

	$(document).on('touchstart', '.item', function () {
		$('.item.touched').each(function () {
			$(this).removeClass('touched');
			$(this).find('video')[0].pause();
		});
		var thisVideo = $(this).find('video');
		var videoSrc = thisVideo.data('src');
		if (videoSrc.length > 5) {
			thisVideo.html('<source src="' + videoSrc + '" type="video/mp4">');
			thisVideo.closest('.item').addClass('touched');
			thisVideo[0].play();
		}
	});

	itemWrapMouseenter();
	/*if ($(document).width() > 991) {
		$('.item__wrap').mouseenter(function(){

			// check and add video with atribute
			if (!$(this).find('video')[0].length) {
				var thisVideo = $(this).find('video');
				var videoSrc = thisVideo.data('src');
				if (videoSrc.length > 5) {
					thisVideo.html('<source src="' + videoSrc + '" type="video/mp4">');
				}
			}

			$(this).find('video')[0].play();
			var cursor = document.createElement('span');
			cursor.classList.add('item__play');
			cursor.innerHTML = 'Играть';
			$(this).find('.item__link').append(cursor);
			$(this).find('.item__play').css('opacity', 1);
			this.addEventListener('mousemove', function gameMouseMove(v) {
				cursor.style.left = v.offsetX - (cursor.offsetWidth / 2) + 'px';
				cursor.style.top = v.offsetY - (cursor.offsetHeight / 2) + 'px';
				this.addEventListener('mouseleave', function() {
					this.removeEventListener('mousemove', gameMouseMove);
					this.style.cursor = 'pointer'
				})
			});
			this.style.cursor = 'none';
		});
		$('.item__wrap').mouseleave(function(){
			if ($(this).find('.item__play')) {
				$(this).find('.item__play').remove();
			}
		});
		$('.item').mouseleave(function(){
			$(this).find('video')[0].pause();
		});
	}*/

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

	autosize($('textarea.form__input'));

	$('.js-close-popup').on('click', function (e) {
		$(this).closest('.item').addClass('hidePopup');
		setTimeout(function () {
			$('.item.hidePopup').removeClass('hidePopup');
		}, 2000);
	});

	$('.js-tabs .tabs__content').first().addClass('isActive');
	$('.js-tabs .tabs__caption--button').first().addClass('isActive');

	$('.js-tabs').on('click', '.tabs__caption--button:not(.isActive)', function () {
		$(this).addClass('isActive').siblings().removeClass('isActive')
			.closest('.tabs').find('.tabs__content.isActive').slideUp().removeClass('isActive').closest('.tabs').find('.tabs__content').eq($(this).index()).slideDown().addClass('isActive');
		var hiddenOnRight = ($(this).offset().left + $(this).width()) > $(window).width();
		var hiddenOnLeft = $(this).offset().left < 0;
		var wrap = $(this).closest('.tabs__caption');
		if (hiddenOnRight) {
			wrap.animate({ scrollLeft: $(this).closest('.tabs__caption').scrollLeft() + $(this).offset().left - 10 }, 200);
		} else if (hiddenOnLeft) {
			wrap.animate({ scrollLeft: $(this).closest('.tabs__caption').scrollLeft() + $(this).offset().left - 10 }, 200);
		}
	});

	$('.js-success').on('click', function (e) {
		e.stopPropagation();
	});


	if ($(document).width() > 991) {

		var itemsOwl = $('.js-carousel-items');
		itemsOwl.on('changed.owl.carousel', function (event) {
			if (event.item.count - event.page.size < event.item.index + 1) {
				$(event.target).addClass('isLast');
			} else {
				$(event.target).removeClass('isLast');
			}
			if (!event.item.index) {
				$(event.target).addClass('isFirst');
			} else {
				$(event.target).removeClass('isFirst');
			}
		});
		itemsOwl.owlCarousel({
			loop: false,
			margin: 0,
			nav: true,
			dots: false,
			slideBy: 2,
			responsive: {
				1199: {
					items: 2,
				},
				991: {
					items: 4,
				},
				992: {
					items: 3,
				},
				0: {
					items: 2,
				}
			}
		});

		$('.js-showmore-items2').on('click', function (e) {
			e.preventDefault();
			$('.js-carousel-items2').addClass('isActive');
			$(this).remove();
		});

		var itemsOwl2 = $('.js-carousel-items2');
		itemsOwl2.on('changed.owl.carousel', function (event) {
			if (event.item.count - event.page.size < event.item.index + 1) {
				$(event.target).addClass('isLast');
			} else {
				$(event.target).removeClass('isLast');
			}
			if (!event.item.index) {
				$(event.target).addClass('isFirst');
			} else {
				$(event.target).removeClass('isFirst');
			}
		});
		itemsOwl2.owlCarousel({
			loop: false,
			margin: 0,
			nav: true,
			dots: false,
			responsive: {
				1199: {
					items: 5
				},
				991: {
					items: 4
				},
				992: {
					items: 3
				},
				0: {
					items: 2
				}
			}
		});
	}

	// автокомплит поиска
	var suggestions = [
		{
			"value": "Стрелялки",
			data: {
				"url": "/product.html",
				"image": "images/item.png",
				"description": "Категория"
			}
		},
		{
			"value": "Стрелок на диком западе",
			data: {
				"url": "/product.html",
				"image": "images/item.png",
				"description": "Игра"
			}
		},
		{
			"value": "Стрелялки",
			data: {
				"url": "/product.html",
				"image": "images/item.png",
				"description": "Категория"
			}
		},
		{
			"value": "Стрелок на диком западе",
			data: {
				"url": "/product.html",
				"image": "images/item.png",
				"description": "Игра"
			}
		},
	];
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
			$('.js-search-top').hide();
			$('.requests-title').hide();
			if (suggestions.length > 2) {
				$(container).find('.autocomplete-suggestion').last().after('<button type="button" onclick="$(\'.search-submitter\').click();" class="searchitem__all button">' + mailLang.show_all + '</button>')
			}
		},
		onHide: function () {
			$('.js-search-top').show();
			$('.requests-title').show();
		}
	});

	$('.search-field-wrap').each(function (i, el) {
		// $("<div class='search-results' style='display: none;'></div>").insertAfter($(this));
	});

	$(document).on('click', '.js-clear', function (e) {
		$(this).prev('input[clearable]').val('').keyup().focus();
		e.stopPropagation();
	});

	$('.js-search-button').on('click', function (e) {
		e.stopPropagation();
		if ($('html').is('.isMenuHovered')) {
			$('.header__menu--item.isVisible').removeClass('isVisible');
			$('html').removeClass('isMenuHovered');
		}
		$('.header__search--form').addClass('isActive');
		$('.js-search-input').val('').focus().removeClass('not-empty');
		$('html').addClass('isSearch')
	});

	$('.header__search--form').on('click', ':not(.js-clear)', function (e) {
		e.stopPropagation();
	});

	$(document).click(function (e) {
		if (!$(e.target).closest('.autocomplete-suggestions').length) {
			closeSearch();
		}
	});

	$('body').on('click', '.js-search-top', function (e) {
		$('.js-search-input').val($(this).find('span').text()).addClass('not-empty').addClass('is-not-empty').autocomplete('onValueChange');
	});

	$('.header__search--close').on('click', function (e) {
		closeSearch();
	});

	function closeSearch() {
		$('.autocomplete-suggestions').hide();
		$('.header__search--form').removeClass('isActive');
		$('html').removeClass('isSearch')
	}

	$('.js-styler').styler({
		select: {
			search: false
		}
	});

	// fancybox defaults
	$('[data-fancybox]').fancybox({
		buttons: ['close'],
		touch: false,
	});

	$('[data-fancybox-popup]').fancybox({
		buttons: ['close'],
		baseClass: 'fancybox-popup',
		touch: false,
	});

	$('[data-fancybox-popup-white]').fancybox({
		buttons: ['close'],
		baseClass: 'fancybox-popup fancybox-popup-white',
		touch: false,
	});

	/*$('[data-fancybox-popup-games]').fancybox({
		buttons : ['close'],
		touch: false,
		baseClass: 'fancybox-games fancybox-popup',
		afterShow: function( instance, current ) {
			$('.fancybox-slide').scroll(function () {
				if ($(this).scrollTop() > 150){
					$('.fancybox-close-small:visible').addClass('isActive');
					$(this).find('.totop').addClass('isActive');
				} else {
					$('.fancybox-close-small:visible').removeClass('isActive');
					$(this).find('.totop').removeClass('isActive');
				}
			});
		}
	});*/

	$(document).on('click', '.js-mygames-totop', function () {
		$('.fancybox-slide:visible').animate({
			scrollTop: 0
		}, 500);
	});

	$('[data-fancybox-change-popup]').on('click', function (e) {
		e.preventDefault();
		var src = $(this).data('src');
		$.fancybox.close();
		$.fancybox.open({
			src: src,
			opts: {
				buttons: ['close'],
				touch: false,
				baseClass: 'fancybox-popup'
			}
		});
	});

	//  add required style on form submit
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
		$.fancybox.close();
		var txt = $(this).data('success-text');
		showAlert(txt, true);
		e.preventDefault();
	});

	$(document).on('submit', 'form[data-success-src]', function (e) {
		// AJAX FUNCTION TO SEND DATA MUST BE HERE
		$.fancybox.close();
		var src = $(this).data('success-src');
		$.fancybox.open({
			src: src,
			opts: {
				touch: false,
				buttons: ['close'],
				baseClass: 'fancybox-popup'
			}
		});
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

	/*$('.js-showpass').on('click', function() {
		if ($(this).is('.isActive')) {
			$(this).removeClass('isActive').text('Показать');
			$(this).next('input').attr('type', 'password')
		} else {
			$(this).addClass('isActive').text('Скрыть');
			$(this).next('input').attr('type', 'text')
		}
	});*/

	document.addEventListener('invalid', (function () {
		return function (e) {
			e.preventDefault();
		};
	})(), true);
});

/**
 * Add link for problem pop-up 
 */
$('#problem').find("a[href='#feedback']").attr('data-fancybox-popup', '');
