@extends('manager.app')
@section('title', !empty($heroes->id) ? 'Редактировать героя' : 'Создать нового героя')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3><i class="icofont icofont-math"></i> {{ !empty($heroes->id) ? 'Редактировать героя' : 'Создать нового героя' }}</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('m.games.heroes') }}">Все герои</a></li>
                        <li class="breadcrumb-item active">{{ !empty($heroes->id) ? 'Редактировать героя' : 'Создать нового героя' }}</li>
                    </ol>
                </div>
                <div class="col-lg-6">
                    <div class="pull-right">
                        <ul>
                            <li><a class="btn btn-dark" href="{{ route('m.games.heroes') }}" data-container="body" data-toggle="popover" data-placement="top" title="" data-original-title="Все герои ">Все герои</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <!-- Notifications -->
        @include('manager.notifications')
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    {{ Form::open(['class' => 'novalidate', 'id' => 'formHeroeEdit', 'method' => 'PUT']) }}
                    <div class="card-body">
                        <div class="row">
                            {!! Form::hidden('_token', csrf_token()) !!}
                            {!! Form::hidden('id', $heroes->id) !!}
                            <div class="col-md-12">
                                <h5><i class="icofont icofont-repair"></i> Основное</h5>
                                <div class="form-group">
                                    <label class="form-label" for="url"> Ключь (алиас)</label>
                                    {{ Form::text('url', old('url', $heroes->url), ['placeholder' => 'URL (алиас)', 'id' => 'url', 'class' => 'form-control', 'maxlength' => 255 ]) }}
                                    <small class="form-text text-muted" id="urlHelp">Оставьте поле пустым если хотите чтобы автоматически создался url</small>
                                    @if ($errors->has('url'))
                                        <div class="badge badge-danger">
                                            <strong>{{ $errors->first('url') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label" for="sort"> Сортировка</label>
                                    {{ Form::number('sort', old('sort', $heroes->sort ?? 0), ['placeholder' => 'сортировка', 'id' => 'sort', 'class' => 'form-control', 'maxlength' => 255]) }}
                                    @if ($errors->has('sort'))
                                        <div class="badge badge-danger">
                                            <strong>{{ $errors->first('sort') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="media m-t-20">
                                    <label class="col-form-label m-r-10" for="public">Опубликован</label>
                                    <div class="media-body icon-state switch-outline">
                                        <label class="switch">
                                            {{ Form::checkbox('public',1, old('public', $heroes->public == 1 ?? 0) == 1, ['id'=>'public']) }}
                                            <span class="switch-state bg-success"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="media m-t-20">
                                    <label class="col-form-label m-r-10" for="show_menu">Показывать в меню</label>
                                    <div class="media-body icon-state switch-outline">
                                        <label class="switch">
                                            {{ Form::checkbox('show_menu',1, old('show_menu', $heroes->show_menu == 1 ?? 0) == 1, ['id'=>'show_menu']) }}
                                            <span class="switch-state bg-success"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="media m-t-20">
                                    <label class="col-form-label m-r-10" for="show_menu">Показывать в футере</label>
                                    <div class="media-body icon-state switch-outline">
                                        <label class="switch">
                                            {{ Form::checkbox('show_footer',1, old('show_footer', $heroes->show_footer == 1 ?? 0) == 1, ['id'=>'show_footer']) }}
                                            <span class="switch-state bg-success"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="general">Главная категория:</label>
                                    <select id="general" name="general" class="form-control">
                                        <option value="0" @if($heroes->general == 0 || old('general') == 0) selected @endif>Не выбрано</option>
                                        @foreach(App\Models\Genre::whereIn('id', \App\Options\GamesGeneralOption::GENERAL_GENRE_HEROES)->get() as $genre)
                                            <option value="{{$genre->id}}" @if($heroes->general == $genre->id || old('general') == $genre->id) selected @endif>{{$genre->getName(1)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <ul class="nav nav-tabs border-tab nav-primary" id="info-tab" role="tablist">
                                    @foreach(App\Models\Language::all() as $lang)
                                        <li class="nav-item"><a class="nav-link {{ $lang->main == 1 ? 'active' : ''}}" id="info-{{ $lang->code }}-tab" data-toggle="tab" href="#info-{{ $lang->code }}" role="tab" aria-controls="info-home" aria-selected="{{ $lang->main == 1 ? 'true' : 'false'}}"><i class="flag-icon flag-icon-{{ $lang->code == 'en' ?'gb' : $lang->code }}"></i></a></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-12">
                                <div class="tab-content" id="info-tabContent">
                                    @foreach(App\Models\Language::all() as $lang)
                                        <div class="tab-pane fade {{ $lang->main == 1 ? 'show active' : ''}}" id="info-{{ $lang->code }}" role="tabpanel" aria-labelledby="info-{{ $lang->code }}-tab">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="h1_{{ $lang->code }}">Название (h1) {!! $lang->main == 1 ? '<span class="text-danger">*</span>' : '' !!}</label>
                                                        {{ Form::text('h1_' . $lang->code, old('h1_' . $lang->code, $langs[$lang->code]->h1), ['placeholder' => 'Введите название героя', 'id' => 'h1_' . $lang->code, 'class' => 'form-control', 'requi' . ($lang->main == 1 ? 'red' : '') => 'required']) }}
                                                        @if ($errors->has('h1_' . $lang->code))
                                                            <div class="badge badge-danger">
                                                                <strong>{{ $errors->first('h1_' . $lang->code) }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="value_{{ $lang->code }}">Название меню</label>
                                                        {{ Form::text('value_' . $lang->code, old('value_' . $lang->code, $langs[$lang->code]->value), ['placeholder' => 'Введите название меню для героя', 'id' => 'value_' . $lang->code, 'class' => 'form-control']) }}
                                                        @if ($errors->has('value_' . $lang->code))
                                                            <div class="badge badge-danger">
                                                                <strong>{{ $errors->first('value_' . $lang->code) }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="description_{{ $lang->code }}">Описание </label>
                                                        {{ Form::textarea('description_' . $lang->code, old('description_' . $lang->code, $langs[$lang->code]->description), ['placeholder' => 'Введите описание жанра', 'id' => 'description_' . $lang->code, 'class' => 'form-control tinymce']) }}
                                                        @if ($errors->has('description_' . $lang->code))
                                                            <div class="badge badge-danger">
                                                                <strong>{{ $errors->first('description_' . $lang->code) }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="h2_{{ $lang->code }}">Название h2 (перед описанием в футоре)</label>
                                                        {{ Form::text('h2_' . $lang->code, old('h2_' . $lang->code, $langs[$lang->code]->h2), ['placeholder' => 'Введите название', 'id' => 'h2_' . $lang->code, 'class' => 'form-control']) }}
                                                        @if ($errors->has('h2_' . $lang->code))
                                                            <div class="badge badge-danger">
                                                                <strong>{{ $errors->first('h2_' . $lang->code) }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label" for="description_buttom_{{ $lang->code }}">Описание в футоре</label>
                                                        {{ Form::textarea('description_buttom_' . $lang->code, old('description_buttom_' . $lang->code, $langs[$lang->code]->description_buttom), ['placeholder' => 'Введите описание жанра отображающей в футоре', 'id' => 'description_buttom_' . $lang->code, 'class' => 'form-control tinymce']) }}
                                                        @if ($errors->has('description_buttom_' . $lang->code))
                                                            <div class="badge badge-danger">
                                                                <strong>{{ $errors->first('description_buttom_' . $lang->code) }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <h5><i class="icofont icofont-optic"></i> SEO</h5>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="seo_title_{{ $lang->code }}">SEO название </label>
                                                        {{ Form::text('seo_title_' . $lang->code, old('seo_title_' . $lang->code, $langs[$lang->code]->seo_title), ['placeholder' => 'Введите SEO название', 'id' => 'seo_title_' . $lang->code, 'class' => 'form-control']) }}
                                                        @if ($errors->has('seo_title_' . $lang->code))
                                                            <div class="badge badge-danger">
                                                                <strong>{{ $errors->first('seo_title_' . $lang->code) }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="seo_description_{{ $lang->code }}">SEO описание</label>
                                                        {{ Form::textarea('seo_description_' . $lang->code, old('seo_description_' . $lang->code, $langs[$lang->code]->seo_description), ['placeholder' => 'Введите SEO описание', 'id' => 'seo_description_' . $lang->code, 'class' => 'form-control']) }}
                                                        @if ($errors->has('seo_description_' . $lang->code))
                                                            <div class="badge badge-danger">
                                                                <strong>{{ $errors->first('seo_description_' . $lang->code) }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h5><i class="icofont icofont-picture"></i> Изображение категории</h5>
                                <small class="gray-color">рекомендуемый размер изображения {{ config('site.heroes.image.width') }}х{{ config('site.heroes.image.height') }}px, изображение автоматически обрежеться если оно будет большего размера</small>
                                @if ($errors->has('image'))
                                    <div class="badge badge-danger">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </div>
                                @endif
                                <div id="mmen" class="m-t-15">
                                    <button id="addImgHeroy" class="btn btn-square btn-outline-primary m-b-15" type="button" data-original-title="@if(!empty($heroes->image)) Изменить @else Добавить @endifизображение" title=""><i class="icon-plus"></i> Добавить изображение</button>
                                    <input type="hidden" name="image" value="{{ old('image') }}">
                                    <div class="avatars">
                                        <div class="avatar">
                                            <img class="img-100" src="{{ old('image', !empty($heroes->image) ? Storage::disk('images')->url($heroes->image) : config('site.heroes.image.default')) }}" alt="#" data-original-title="" title="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('m.games.heroes') }}" class="btn btn-dark">Закрыть</a>
                        <button class="btn btn-primary" type="submit">Сохраить</button>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        @endsection
        @section('stylesheet')
            <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/elfinder/js/jquery-ui-1.8.13.custom.css') }}">
            <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/elfinder/css/elfinder.min.css') }}">
            <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/elfinder/css/theme.css') }}">
        @endsection
        @section('script')
            {{--<script src="https://code.jquery.com/jquery-1.9.0.js"></script>--}}
            {{--<script src="https://code.jquery.com/jquery-migrate-1.2.1.js"></script>--}}
            <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
            <script type="text/javascript" src="{{ asset('assets/elfinder/js/elfinder.min.js') }}"></script>
            <script type="text/javascript" src="{{ asset('assets/elfinder/js/extras/editors.default.min.js') }}"></script>
            <script type="text/javascript" src="{{ asset('assets/elfinder/js/i18n/elfinder.ru.js') }}"></script>
            <script type="application/javascript">

                jQuery.browser = {};
                (function () {
                    jQuery.browser.msie = false;
                    jQuery.browser.version = 0;
                    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
                        jQuery.browser.msie = true;
                        jQuery.browser.version = RegExp.$1;
                    }
                })();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    error : function(jqXHR, textStatus, errorThrown) {
                        var errorMess = " Ошибка: " + textStatus + ": " + errorThrown
                        if (jqXHR.status == 404) {
                            errorMess = "Не найденн урл для запроса";
                        }

                        $.notify({
                            title:'Ошибка',
                            message:errorMess,
                        }, {
                            type:'danger',
                            mouse_over:true,
                        });
                    }
                });

                var addImgGenre = $('#addImgHeroy');

                addImgGenre.click(function() {
                    $('<div />').dialogelfinder({
                        url: '/assets/elfinder/php/connector.minimal.php',
                        lang : 'ru',
                        commandsOptions: {
                            getfile: {
                                onlyURL  : false,
                                oncomplete: 'destroy' // destroy elFinder after file selection
                            }
                        },
                        getFileCallback: function( file, fm ){
                            $('#mmen').find('img').attr('src', file['url']);
                            $('input[name="image"]').val(file['url']);
                        }
                    });
                });

            </script>
    @include('include.js.tinymce')
@endsection