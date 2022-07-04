@extends('layouts.app')

@section('content')
    <div class="title">
        <div class="title__h2">Просмотренные игры</div>
    </div>
    <div class="items row">
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <div class="item">
                <button class="item__delete" type="button"><span class="icon-close"></span></button>
                <div class="item__wrap">
                    <a href="/game.html" class="item__link"></a>
                    <div class="item__img">
                        <div class="item__label">
                            <!-- чтоб был лейб new - просто написать тут текст вместо иконки -->
                            <span class="icon-fire"></span>
                        </div>
                        <!--			<button class="item__fav isActive" type="button"><span class="icon-star"></span></button>-->
                        <div class="item__percent">
                            <div class="percent-circle js-circle" data-percent="20"><svg><use class="percent-circle-inner" xlink:href="#percent-circle-svg"></use></svg></div>
                        </div>
                        <!--			<button class="item__play" type="button">Играть</button>-->
                        <div class="item__preview">
                            <video muted loop>
                                <!-- MP4 must be first for iPad! -->
                                <source src="https://clips.vorwaerts-gmbh.de/VfE_html5.mp4" type="video/mp4">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.webm" type="video/webm">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.ogv" type="video/ogg">
                            </video>
                        </div>
                        <img src="/images/item.png">
                    </div>
                    <div class="item__name">Игра мечи и сани</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <div class="item">
                <button class="item__delete" type="button"><span class="icon-close"></span></button>
                <div class="item__wrap">
                    <a href="/game.html" class="item__link"></a>
                    <div class="item__img">
                        <button class="item__fav isActive" type="button"><span class="icon-star"></span></button>
                        <div class="item__percent">
                            <div class="percent-circle js-circle" data-percent="20"><svg><use class="percent-circle-inner" xlink:href="#percent-circle-svg"></use></svg></div>
                        </div>
                        <!--			<button class="item__play" type="button">Играть</button>-->
                        <div class="item__preview">
                            <video muted loop>
                                <!-- MP4 must be first for iPad! -->
                                <source src="https://clips.vorwaerts-gmbh.de/VfE_html5.mp4" type="video/mp4">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.webm" type="video/webm">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.ogv" type="video/ogg">
                            </video>
                        </div>
                        <img src="/images/item.png">
                    </div>
                    <div class="item__name">Игра мечи и сани</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <div class="item">
                <button class="item__delete" type="button"><span class="icon-close"></span></button>
                <div class="item__wrap">
                    <a href="/game.html" class="item__link"></a>
                    <div class="item__img">
                        <div class="item__label">
                            <!-- чтоб был лейб new - просто написать тут текст вместо иконки -->
                            <span class="icon-fire"></span>
                        </div>
                        <!--			<button class="item__fav isActive" type="button"><span class="icon-star"></span></button>-->
                        <div class="item__percent">
                            <div class="percent-circle js-circle" data-percent="20"><svg><use class="percent-circle-inner" xlink:href="#percent-circle-svg"></use></svg></div>
                        </div>
                        <!--			<button class="item__play" type="button">Играть</button>-->
                        <div class="item__preview">
                            <video muted loop>
                                <!-- MP4 must be first for iPad! -->
                                <source src="https://clips.vorwaerts-gmbh.de/VfE_html5.mp4" type="video/mp4">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.webm" type="video/webm">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.ogv" type="video/ogg">
                            </video>
                        </div>
                        <img src="/images/item.png">
                    </div>
                    <div class="item__name">Игра мечи и сани</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <div class="item">
                <button class="item__delete" type="button"><span class="icon-close"></span></button>
                <div class="item__wrap">
                    <a href="/game.html" class="item__link"></a>
                    <div class="item__img">
                        <button class="item__fav isActive" type="button"><span class="icon-star"></span></button>
                        <div class="item__percent">
                            <div class="percent-circle js-circle" data-percent="20"><svg><use class="percent-circle-inner" xlink:href="#percent-circle-svg"></use></svg></div>
                        </div>
                        <!--			<button class="item__play" type="button">Играть</button>-->
                        <div class="item__preview">
                            <video muted loop>
                                <!-- MP4 must be first for iPad! -->
                                <source src="https://clips.vorwaerts-gmbh.de/VfE_html5.mp4" type="video/mp4">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.webm" type="video/webm">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.ogv" type="video/ogg">
                            </video>
                        </div>
                        <img src="/images/item.png">
                    </div>
                    <div class="item__name">Игра мечи и сани</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <div class="item">
                <button class="item__delete" type="button"><span class="icon-close"></span></button>
                <div class="item__wrap">
                    <a href="/game.html" class="item__link"></a>
                    <div class="item__img">
                        <div class="item__label">
                            <!-- чтоб был лейб new - просто написать тут текст вместо иконки -->
                            <span class="icon-fire"></span>
                        </div>
                        <!--			<button class="item__fav isActive" type="button"><span class="icon-star"></span></button>-->
                        <div class="item__percent">
                            <div class="percent-circle js-circle" data-percent="20"><svg><use class="percent-circle-inner" xlink:href="#percent-circle-svg"></use></svg></div>
                        </div>
                        <!--			<button class="item__play" type="button">Играть</button>-->
                        <div class="item__preview">
                            <video muted loop>
                                <!-- MP4 must be first for iPad! -->
                                <source src="https://clips.vorwaerts-gmbh.de/VfE_html5.mp4" type="video/mp4">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.webm" type="video/webm">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.ogv" type="video/ogg">
                            </video>
                        </div>
                        <img src="/images/item.png">
                    </div>
                    <div class="item__name">Игра мечи и сани</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <div class="item">
                <button class="item__delete" type="button"><span class="icon-close"></span></button>
                <div class="item__wrap">
                    <a href="/game.html" class="item__link"></a>
                    <div class="item__img">
                        <button class="item__fav isActive" type="button"><span class="icon-star"></span></button>
                        <div class="item__percent">
                            <div class="percent-circle js-circle" data-percent="20"><svg><use class="percent-circle-inner" xlink:href="#percent-circle-svg"></use></svg></div>
                        </div>
                        <!--			<button class="item__play" type="button">Играть</button>-->
                        <div class="item__preview">
                            <video muted loop>
                                <!-- MP4 must be first for iPad! -->
                                <source src="https://clips.vorwaerts-gmbh.de/VfE_html5.mp4" type="video/mp4">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.webm" type="video/webm">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.ogv" type="video/ogg">
                            </video>
                        </div>
                        <img src="/images/item.png">
                    </div>
                    <div class="item__name">Игра мечи и сани</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <div class="item">
                <button class="item__delete" type="button"><span class="icon-close"></span></button>
                <div class="item__wrap">
                    <a href="/game.html" class="item__link"></a>
                    <div class="item__img">
                        <div class="item__label">
                            <!-- чтоб был лейб new - просто написать тут текст вместо иконки -->
                            <span class="icon-fire"></span>
                        </div>
                        <!--			<button class="item__fav isActive" type="button"><span class="icon-star"></span></button>-->
                        <div class="item__percent">
                            <div class="percent-circle js-circle" data-percent="20"><svg><use class="percent-circle-inner" xlink:href="#percent-circle-svg"></use></svg></div>
                        </div>
                        <!--			<button class="item__play" type="button">Играть</button>-->
                        <div class="item__preview">
                            <video muted loop>
                                <!-- MP4 must be first for iPad! -->
                                <source src="https://clips.vorwaerts-gmbh.de/VfE_html5.mp4" type="video/mp4">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.webm" type="video/webm">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.ogv" type="video/ogg">
                            </video>
                        </div>
                        <img src="/images/item.png">
                    </div>
                    <div class="item__name">Игра мечи и сани</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <div class="item">
                <button class="item__delete" type="button"><span class="icon-close"></span></button>
                <div class="item__wrap">
                    <a href="/game.html" class="item__link"></a>
                    <div class="item__img">
                        <button class="item__fav isActive" type="button"><span class="icon-star"></span></button>
                        <div class="item__percent">
                            <div class="percent-circle js-circle" data-percent="20"><svg><use class="percent-circle-inner" xlink:href="#percent-circle-svg"></use></svg></div>
                        </div>
                        <!--			<button class="item__play" type="button">Играть</button>-->
                        <div class="item__preview">
                            <video muted loop>
                                <!-- MP4 must be first for iPad! -->
                                <source src="https://clips.vorwaerts-gmbh.de/VfE_html5.mp4" type="video/mp4">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.webm" type="video/webm">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.ogv" type="video/ogg">
                            </video>
                        </div>
                        <img src="/images/item.png">
                    </div>
                    <div class="item__name">Игра мечи и сани</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <div class="item">
                <button class="item__delete" type="button"><span class="icon-close"></span></button>
                <div class="item__wrap">
                    <a href="/game.html" class="item__link"></a>
                    <div class="item__img">
                        <div class="item__label">
                            <!-- чтоб был лейб new - просто написать тут текст вместо иконки -->
                            <span class="icon-fire"></span>
                        </div>
                        <!--			<button class="item__fav isActive" type="button"><span class="icon-star"></span></button>-->
                        <div class="item__percent">
                            <div class="percent-circle js-circle" data-percent="20"><svg><use class="percent-circle-inner" xlink:href="#percent-circle-svg"></use></svg></div>
                        </div>
                        <!--			<button class="item__play" type="button">Играть</button>-->
                        <div class="item__preview">
                            <video muted loop>
                                <!-- MP4 must be first for iPad! -->
                                <source src="https://clips.vorwaerts-gmbh.de/VfE_html5.mp4" type="video/mp4">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.webm" type="video/webm">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.ogv" type="video/ogg">
                            </video>
                        </div>
                        <img src="/images/item.png">
                    </div>
                    <div class="item__name">Игра мечи и сани</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <div class="item">
                <button class="item__delete" type="button"><span class="icon-close"></span></button>
                <div class="item__wrap">
                    <a href="/game.html" class="item__link"></a>
                    <div class="item__img">
                        <button class="item__fav isActive" type="button"><span class="icon-star"></span></button>
                        <div class="item__percent">
                            <div class="percent-circle js-circle" data-percent="20"><svg><use class="percent-circle-inner" xlink:href="#percent-circle-svg"></use></svg></div>
                        </div>
                        <!--			<button class="item__play" type="button">Играть</button>-->
                        <div class="item__preview">
                            <video muted loop>
                                <!-- MP4 must be first for iPad! -->
                                <source src="https://clips.vorwaerts-gmbh.de/VfE_html5.mp4" type="video/mp4">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.webm" type="video/webm">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.ogv" type="video/ogg">
                            </video>
                        </div>
                        <img src="/images/item.png">
                    </div>
                    <div class="item__name">Игра мечи и сани</div>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="loadmore js-load-all-viewed">Все новые игры</button>

    <br>

    <div class="description">
        <p>Lorem Ipsum - это текст-"рыба", часто используемый в печати и вэб-дизайне. Lorem Ipsum является стандартной "рыбой" для текстов на латинице с начала XVI века. В то время некий безымянный печатник создал большую коллекцию размеров и форм шрифтов, используя Lorem Ipsum для распечатки образцов. Lorem Ipsum не только успешно пережил без заметных изменений пять веков, но и перешагнул в</p>
    </div>

    <div class="title">
        <div class="title__h2">Популярные категории</div>
    </div>
    <div class="cats row">
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <a href="/catalog.html" class="cat">
                <img src="/images/item.png" class="cat__img">
                <span class="cat__name"><span>Выход из комнаты из комнаты из комнаты из комнаты</span></span>
            </a>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <a href="/catalog.html" class="cat">
                <img src="/images/item.png" class="cat__img">
                <span class="cat__name"><span>Выход из комнаты из комнаты из комнаты из комнаты</span></span>
            </a>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <a href="/catalog.html" class="cat">
                <img src="/images/item.png" class="cat__img">
                <span class="cat__name"><span>Выход из комнаты из комнаты из комнаты из комнаты</span></span>
            </a>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <a href="/catalog.html" class="cat">
                <img src="/images/item.png" class="cat__img">
                <span class="cat__name"><span>Выход из комнаты из комнаты из комнаты из комнаты</span></span>
            </a>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-12 cats__all">
            <a href="#" class="cat__all js-show-cats"><span>Показать все</span></a>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <a href="/catalog.html" class="cat">
                <img src="/images/item.png" class="cat__img">
                <span class="cat__name"><span>Выход из комнаты из комнаты из комнаты из комнаты</span></span>
            </a>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <a href="/catalog.html" class="cat">
                <img src="/images/item.png" class="cat__img">
                <span class="cat__name"><span>Выход из комнаты из комнаты из комнаты из комнаты</span></span>
            </a>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <a href="/catalog.html" class="cat">
                <img src="/images/item.png" class="cat__img">
                <span class="cat__name"><span>Выход из комнаты из комнаты из комнаты из комнаты</span></span>
            </a>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <a href="/catalog.html" class="cat">
                <img src="/images/item.png" class="cat__img">
                <span class="cat__name"><span>Выход из комнаты из комнаты из комнаты из комнаты</span></span>
            </a>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <a href="/catalog.html" class="cat">
                <img src="/images/item.png" class="cat__img">
                <span class="cat__name"><span>Выход из комнаты из комнаты из комнаты из комнаты</span></span>
            </a>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <a href="/catalog.html" class="cat">
                <img src="/images/item.png" class="cat__img">
                <span class="cat__name"><span>Выход из комнаты из комнаты из комнаты из комнаты</span></span>
            </a>
        </div>
    </div>

    <div class="title">
        <h1 class="title__h2">Игры онлайн бесплатно</h1>
        <div class="title__right">
            <a href="#" class="title__right--link isActive">Новые</a>
            <a href="#" class="title__right--link">Лучшие</a>
        </div>
    </div>

    <div class="visible-xs mobcats js-mobcats">
        <div class="mobcats__wrap">
            <a href="#" class="mobcats__link isActive">Новые игры<i></i></a>
            <a href="#" class="mobcats__link">Лучшие игры<i></i></a>
        </div>
    </div>

    <div class="items row">
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <div class="item">
                <button class="item__delete" type="button"><span class="icon-close"></span></button>
                <div class="item__wrap">
                    <a href="/game.html" class="item__link"></a>
                    <div class="item__img">
                        <div class="item__label">
                            <!-- чтоб был лейб new - просто написать тут текст вместо иконки -->
                            <span class="icon-fire"></span>
                        </div>
                        <!--			<button class="item__fav isActive" type="button"><span class="icon-star"></span></button>-->
                        <div class="item__percent">
                            <div class="percent-circle js-circle" data-percent="20"><svg><use class="percent-circle-inner" xlink:href="#percent-circle-svg"></use></svg></div>
                        </div>
                        <!--			<button class="item__play" type="button">Играть</button>-->
                        <div class="item__preview">
                            <video muted loop>
                                <!-- MP4 must be first for iPad! -->
                                <source src="https://clips.vorwaerts-gmbh.de/VfE_html5.mp4" type="video/mp4">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.webm" type="video/webm">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.ogv" type="video/ogg">
                            </video>
                        </div>
                        <img src="/images/item.png">
                    </div>
                    <div class="item__name">Игра мечи и сани</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <div class="item">
                <button class="item__delete" type="button"><span class="icon-close"></span></button>
                <div class="item__wrap">
                    <a href="/game.html" class="item__link"></a>
                    <div class="item__img">
                        <button class="item__fav isActive" type="button"><span class="icon-star"></span></button>
                        <div class="item__percent">
                            <div class="percent-circle js-circle" data-percent="20"><svg><use class="percent-circle-inner" xlink:href="#percent-circle-svg"></use></svg></div>
                        </div>
                        <!--			<button class="item__play" type="button">Играть</button>-->
                        <div class="item__preview">
                            <video muted loop>
                                <!-- MP4 must be first for iPad! -->
                                <source src="https://clips.vorwaerts-gmbh.de/VfE_html5.mp4" type="video/mp4">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.webm" type="video/webm">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.ogv" type="video/ogg">
                            </video>
                        </div>
                        <img src="/images/item.png">
                    </div>
                    <div class="item__name">Игра мечи и сани</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <div class="item">
                <button class="item__delete" type="button"><span class="icon-close"></span></button>
                <div class="item__wrap">
                    <a href="/game.html" class="item__link"></a>
                    <div class="item__img">
                        <div class="item__label">
                            <!-- чтоб был лейб new - просто написать тут текст вместо иконки -->
                            <span class="icon-fire"></span>
                        </div>
                        <!--			<button class="item__fav isActive" type="button"><span class="icon-star"></span></button>-->
                        <div class="item__percent">
                            <div class="percent-circle js-circle" data-percent="20"><svg><use class="percent-circle-inner" xlink:href="#percent-circle-svg"></use></svg></div>
                        </div>
                        <!--			<button class="item__play" type="button">Играть</button>-->
                        <div class="item__preview">
                            <video muted loop>
                                <!-- MP4 must be first for iPad! -->
                                <source src="https://clips.vorwaerts-gmbh.de/VfE_html5.mp4" type="video/mp4">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.webm" type="video/webm">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.ogv" type="video/ogg">
                            </video>
                        </div>
                        <img src="/images/item.png">
                    </div>
                    <div class="item__name">Игра мечи и сани</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <div class="item">
                <button class="item__delete" type="button"><span class="icon-close"></span></button>
                <div class="item__wrap">
                    <a href="/game.html" class="item__link"></a>
                    <div class="item__img">
                        <button class="item__fav isActive" type="button"><span class="icon-star"></span></button>
                        <div class="item__percent">
                            <div class="percent-circle js-circle" data-percent="20"><svg><use class="percent-circle-inner" xlink:href="#percent-circle-svg"></use></svg></div>
                        </div>
                        <!--			<button class="item__play" type="button">Играть</button>-->
                        <div class="item__preview">
                            <video muted loop>
                                <!-- MP4 must be first for iPad! -->
                                <source src="https://clips.vorwaerts-gmbh.de/VfE_html5.mp4" type="video/mp4">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.webm" type="video/webm">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.ogv" type="video/ogg">
                            </video>
                        </div>
                        <img src="/images/item.png">
                    </div>
                    <div class="item__name">Игра мечи и сани</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <div class="item">
                <button class="item__delete" type="button"><span class="icon-close"></span></button>
                <div class="item__wrap">
                    <a href="/game.html" class="item__link"></a>
                    <div class="item__img">
                        <div class="item__label">
                            <!-- чтоб был лейб new - просто написать тут текст вместо иконки -->
                            <span class="icon-fire"></span>
                        </div>
                        <!--			<button class="item__fav isActive" type="button"><span class="icon-star"></span></button>-->
                        <div class="item__percent">
                            <div class="percent-circle js-circle" data-percent="20"><svg><use class="percent-circle-inner" xlink:href="#percent-circle-svg"></use></svg></div>
                        </div>
                        <!--			<button class="item__play" type="button">Играть</button>-->
                        <div class="item__preview">
                            <video muted loop>
                                <!-- MP4 must be first for iPad! -->
                                <source src="https://clips.vorwaerts-gmbh.de/VfE_html5.mp4" type="video/mp4">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.webm" type="video/webm">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.ogv" type="video/ogg">
                            </video>
                        </div>
                        <img src="/images/item.png">
                    </div>
                    <div class="item__name">Игра мечи и сани</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <div class="item">
                <button class="item__delete" type="button"><span class="icon-close"></span></button>
                <div class="item__wrap">
                    <a href="/game.html" class="item__link"></a>
                    <div class="item__img">
                        <button class="item__fav isActive" type="button"><span class="icon-star"></span></button>
                        <div class="item__percent">
                            <div class="percent-circle js-circle" data-percent="20"><svg><use class="percent-circle-inner" xlink:href="#percent-circle-svg"></use></svg></div>
                        </div>
                        <!--			<button class="item__play" type="button">Играть</button>-->
                        <div class="item__preview">
                            <video muted loop>
                                <!-- MP4 must be first for iPad! -->
                                <source src="https://clips.vorwaerts-gmbh.de/VfE_html5.mp4" type="video/mp4">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.webm" type="video/webm">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.ogv" type="video/ogg">
                            </video>
                        </div>
                        <img src="/images/item.png">
                    </div>
                    <div class="item__name">Игра мечи и сани</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <div class="item">
                <button class="item__delete" type="button"><span class="icon-close"></span></button>
                <div class="item__wrap">
                    <a href="/game.html" class="item__link"></a>
                    <div class="item__img">
                        <div class="item__label">
                            <!-- чтоб был лейб new - просто написать тут текст вместо иконки -->
                            <span class="icon-fire"></span>
                        </div>
                        <!--			<button class="item__fav isActive" type="button"><span class="icon-star"></span></button>-->
                        <div class="item__percent">
                            <div class="percent-circle js-circle" data-percent="20"><svg><use class="percent-circle-inner" xlink:href="#percent-circle-svg"></use></svg></div>
                        </div>
                        <!--			<button class="item__play" type="button">Играть</button>-->
                        <div class="item__preview">
                            <video muted loop>
                                <!-- MP4 must be first for iPad! -->
                                <source src="https://clips.vorwaerts-gmbh.de/VfE_html5.mp4" type="video/mp4">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.webm" type="video/webm">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.ogv" type="video/ogg">
                            </video>
                        </div>
                        <img src="/images/item.png">
                    </div>
                    <div class="item__name">Игра мечи и сани</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <div class="item">
                <button class="item__delete" type="button"><span class="icon-close"></span></button>
                <div class="item__wrap">
                    <a href="/game.html" class="item__link"></a>
                    <div class="item__img">
                        <button class="item__fav isActive" type="button"><span class="icon-star"></span></button>
                        <div class="item__percent">
                            <div class="percent-circle js-circle" data-percent="20"><svg><use class="percent-circle-inner" xlink:href="#percent-circle-svg"></use></svg></div>
                        </div>
                        <!--			<button class="item__play" type="button">Играть</button>-->
                        <div class="item__preview">
                            <video muted loop>
                                <!-- MP4 must be first for iPad! -->
                                <source src="https://clips.vorwaerts-gmbh.de/VfE_html5.mp4" type="video/mp4">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.webm" type="video/webm">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.ogv" type="video/ogg">
                            </video>
                        </div>
                        <img src="/images/item.png">
                    </div>
                    <div class="item__name">Игра мечи и сани</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <div class="item">
                <button class="item__delete" type="button"><span class="icon-close"></span></button>
                <div class="item__wrap">
                    <a href="/game.html" class="item__link"></a>
                    <div class="item__img">
                        <div class="item__label">
                            <!-- чтоб был лейб new - просто написать тут текст вместо иконки -->
                            <span class="icon-fire"></span>
                        </div>
                        <!--			<button class="item__fav isActive" type="button"><span class="icon-star"></span></button>-->
                        <div class="item__percent">
                            <div class="percent-circle js-circle" data-percent="20"><svg><use class="percent-circle-inner" xlink:href="#percent-circle-svg"></use></svg></div>
                        </div>
                        <!--			<button class="item__play" type="button">Играть</button>-->
                        <div class="item__preview">
                            <video muted loop>
                                <!-- MP4 must be first for iPad! -->
                                <source src="https://clips.vorwaerts-gmbh.de/VfE_html5.mp4" type="video/mp4">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.webm" type="video/webm">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.ogv" type="video/ogg">
                            </video>
                        </div>
                        <img src="/images/item.png">
                    </div>
                    <div class="item__name">Игра мечи и сани</div>
                </div>
            </div>
        </div>
        <div class="col-lg-5ths col-md-3 col-sm-4 col-xs-6">
            <div class="item">
                <button class="item__delete" type="button"><span class="icon-close"></span></button>
                <div class="item__wrap">
                    <a href="/game.html" class="item__link"></a>
                    <div class="item__img">
                        <button class="item__fav isActive" type="button"><span class="icon-star"></span></button>
                        <div class="item__percent">
                            <div class="percent-circle js-circle" data-percent="20"><svg><use class="percent-circle-inner" xlink:href="#percent-circle-svg"></use></svg></div>
                        </div>
                        <!--			<button class="item__play" type="button">Играть</button>-->
                        <div class="item__preview">
                            <video muted loop>
                                <!-- MP4 must be first for iPad! -->
                                <source src="https://clips.vorwaerts-gmbh.de/VfE_html5.mp4" type="video/mp4">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.webm" type="video/webm">
                                <source src="https://clips.vorwaerts-gmbh.de/VfE.ogv" type="video/ogg">
                            </video>
                        </div>
                        <img src="/images/item.png">
                    </div>
                    <div class="item__name">Игра мечи и сани</div>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="loadmore">Показать все новые</button>

    <div class="pagination">
        <div class="pagination__wrap">
            <a href="#" class="pagination__button"><span class="icon-arrow-left"></span></a>
            <a href="#" class="pagination__link">1</a>
            <a href="#" class="pagination__link">2</a>
            <a href="#" class="pagination__link isActive">3</a>
            <a href="#" class="pagination__link">...</a>
            <a href="#" class="pagination__link">34</a>
            <a href="#" class="pagination__link">34</a>
            <a href="#" class="pagination__link">34</a>
            <a href="#" class="pagination__link">34</a>
            <a href="#" class="pagination__link">34</a>
            <a href="#" class="pagination__button isActive"><span class="icon-arrow-right"></span></a>
        </div>
    </div>

    <div class="description">
        <h2>Баскетбол</h2>
        <p>Lorem Ipsum - это текст-"рыба", часто используемый в печати и вэб-дизайне. Lorem Ipsum является стандартной "рыбой" для текстов на латинице с начала XVI века. В то время некий безымянный печатник создал большую коллекцию размеров и форм шрифтов, используя Lorem Ipsum для распечатки образцов. Lorem Ipsum не только успешно пережил без заметных изменений пять веков, но и перешагнул в</p>
    </div>
@endsection