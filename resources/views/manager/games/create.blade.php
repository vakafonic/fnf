@extends('manager.app')
@section('title', $title)
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3><i class="icofont icofont-game-control"></i> {{ $title }}</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('m.games.create') }}">Все игры</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
                <div class="col-lg-6">
                    <div class="pull-right">
                        <ul>
                            @if(!empty($game->url))
                            <li class="pull-right m-l-15"><a class="btn btn-primary" target="_blank" href="{{ config('app.url') }}ru/{{ $game->url }}/" data-container="body" data-toggle="popover" data-placement="top" title="" data-original-title="Посмотреть на сайте "><span class="icon-eye"></span> Посмотреть</a></li>
                            @endif
                            <li class="pull-right"><a class="btn btn-dark" href="{{ route('m.games.index') }}" data-container="body" data-toggle="popover" data-placement="top" title="" data-original-title="Все герои ">Все игры</a></li>
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
                    {{ Form::open(['class' => 'novalidate', 'id' => 'formGameEdit', 'multiple'=>true, 'files'=>true]) }}
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3 tabs-responsive-side">
                                <div class="nav flex-column nav-pills border-tab nav-left" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Основное</a>
                                    @if(!$translator)
                                    <a class="nav-link" id="v-pills-categories-tab" data-toggle="pill" href="#v-pills-categories" role="tab" aria-controls="v-pills-categories" aria-selected="false">Жанры</a>
                                    <a class="nav-link" id="v-pills-heroes-tab" data-toggle="pill" href="#v-pills-heroes" role="tab" aria-controls="v-pills-heroes" aria-selected="false">Герои</a>
                                    @endif
                                    <a class="nav-link" id="v-pills-command-tab" data-toggle="pill" href="#v-pills-command" role="tab" aria-controls="v-pills-command" aria-selected="false">Управление</a>
                                    @if(!$translator)
                                    <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">SEO</a>
                                    <a class="nav-link" id="v-pills-gallery-tab" data-toggle="pill" href="#v-pills-gallery" role="tab" aria-controls="v-pills-gallery" aria-selected="false">Галерея</a>
                                    <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Настройки</a>
                                    <a class="nav-link" id="v-pills-similar-tab" data-toggle="pill" href="#v-pills-similar" role="tab" aria-controls="v-pills-similar" aria-selected="false">Игры из этой серии ({{$countSimilars}})</a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="tab-content" id="v-pills-tabContent">
                                    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                                        @if(!$translator)
                                        <div class="row">
                                            @if(Auth::user()->isRoleAction('games_public'))
                                            <div class="col-md-4">
                                                <div class="media m-t-20">
                                                    <label class="col-form-label m-r-10" for="public">Опубликована</label>
                                                    <div class="media-body icon-state switch-outline">
                                                        <label class="switch">
                                                            {{ Form::checkbox('public',1, old('public', $game->public) == 1, ['id'=>'public']) }}
                                                            <span class="switch-state bg-success"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                                @if(Auth::user()->isRoleAction('games_manualrating'))
                                                <div class="col-md-4">
                                                    <div class="media m-t-20">
                                                        <label class="col-form-label m-r-10" for="public">Хорошая игра</label>
                                                        <div class="media-body icon-state switch-outline">
                                                            <label class="switch">
                                                                {{ Form::checkbox('good',1, old('good', $game->good) == 1, ['id'=>'good']) }}
                                                                <span class="switch-state bg-success"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="media m-t-20">
                                                        <label class="col-form-label m-r-10" for="public">Топ игра</label>
                                                        <div class="media-body icon-state switch-outline">
                                                            <label class="switch">
                                                                {{ Form::checkbox('top',1, old('top', $game->top) == 1, ['id'=>'top']) }}
                                                                <span class="switch-state bg-success"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                <input type="hidden" name="sort" value="{{ old('sort', $game->sort) }}">

                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="scrollable-dropdown-menu">
                                                    <div class="form-group">
                                                        <label class="form-label" for="sort"> Разработчик</label>
                                                        {{ Form::text('developer_name', old('developer_name', $game->developer_name), ['placeholder' => 'Введите название разработчика', 'id' => 'developer_name', 'class' => 'typeahead form-control', 'autocomplete' => 'off']) }}
                                                        <small class="form-text text-muted" id="urlHelp">введите или выберите из списка разработчика или введите новое имя и разработчик будет создан автоматически</small>
                                                        @if ($errors->has('developer_name'))
                                                            <div class="badge badge-danger">
                                                                <strong>{{ $errors->first('developer_name') }}</strong>
                                                            </div>
                                                        @endif
                                                     </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        {{--start tab top--}}
                                        <div class="row">
                                            <div class="col-md-12">
                                                <ul class="nav nav-tabs border-tab nav-primary" id="info-tab" role="tablist">
                                                    @foreach(App\Models\Language::all() as $lang)
                                                        <li class="nav-item"><a class="nav-link {{ $lang->code == 'ru' ? 'active' : ''}}" id="info-{{ $lang->code }}-tab" data-toggle="tab" href="#info-{{ $lang->code }}" role="tab" aria-controls="info-home" aria-selected="{{ $lang->main == 1 ? 'true' : 'false'}}"><i class="flag-icon flag-icon-{{ $lang->code == 'en' ?'gb' : ($lang->code == 'uk' ? 'ua' : $lang->code) }}"></i></a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="tab-content" id="info-tabContent">
                                                    @foreach(App\Models\Language::all() as $lang)
                                                        <div class="tab-pane fade {{ $lang->code == 'ru' ? 'show active' : ''}}" id="info-{{ $lang->code }}" role="tabpanel" aria-labelledby="info-{{ $lang->code }}-tab">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="name_{{ $lang->code }}">Название {!! $lang->main == 1 ? '<span class="text-danger">*</span>' : '' !!}</label>
                                                                        {{ Form::text('name_' . $lang->code, old('name_' . $lang->code, $langs[$lang->code]->name), ['placeholder' => 'Введите название игры', 'id' => 'name_' . $lang->code, 'class' => 'form-control' . ($translator ? ' nocopy' : ''), 'requi' . ($lang->main == 1 ? 'red' : '') => 'required', 're' . ($translator ? 'adonly' : '' ) => 'readonly']) }}
                                                                        @if ($errors->has('name_' . $lang->code))
                                                                            <div class="badge badge-danger">
                                                                                <strong>{{ $errors->first('name_' . $lang->code) }}</strong>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="description_{{ $lang->code }}">Описание </label><span class="float-right text-secondary font-weight-light font-italic ssp_{{ $lang->code }}"></span>
                                                                        {{ Form::textarea('description_' . $lang->code, old('description_' . $lang->code, $langs[$lang->code]->description), ['placeholder' => 'Введите описание игры', 'id' => 'description_' . $lang->code, 'class' => 'form-control' . ($translator && $lang->id == 1 ? ' nocopy' : ''), 're' . ($translator && $lang->id == 1 ? 'adonly' : '' ) => 'readonly', 'minlength' => config('site.game.min_description')]) }}
                                                                        @if ($errors->has('description_' . $lang->code))
                                                                            <div class="badge badge-danger">
                                                                                <strong>{{ $errors->first('description_' . $lang->code) }}</strong>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="how_play_{{ $lang->code }}">Как играть </label><span class="float-right text-secondary font-weight-light font-italic ssk_{{ $lang->code }}"></span>
                                                                        {{ Form::textarea('how_play_' . $lang->code, old('how_play_' . $lang->code, $langs[$lang->code]->how_play), ['placeholder' => 'Введите описание как играть', 'id' => 'how_play_' . $lang->code, 'class' => 'form-control' . ($translator && $lang->id == 1 ? ' nocopy' : ''), 're' . ($translator && $lang->id == 1 ? 'adonly' : '' ) => 'readonly', 'minlength' => config('site.game.min_how_play')]) }}
                                                                        @if ($errors->has('how_play_' . $lang->code))
                                                                            <div class="badge badge-danger">
                                                                                <strong>{{ $errors->first('how_play_' . $lang->code) }}</strong>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            {{--end tab top--}}
                                        </div>
                                    </div>
{{--                                    @if(!$translator)--}}
                                    <div class="tab-pane fade" id="v-pills-categories" role="tabpanel" aria-labelledby="v-pills-categories-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <button onclick="GamesPage.unceck('#treecheckbox')" class="btn btn-primary btn-sm" type="button" data-original-title="" title=""><span class="icon-close"></span> Снять все</button>
                                                <hr>
                                                <label>Категории</label>
                                                {!! Form::hidden('genre_add', '', ['id' => 'genre_add']) !!}
                                                <div id="treecheckbox">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="general_category">Главная категория:</label>
                                                <select name="general_category" id="general_category">
                                                    @if($generalGenre == 0)
                                                        <option value="0" disabled selected>Укажите главную категорию</option>
                                                    @endif
                                                    @foreach($genresAll as $genre)
                                                        <option value="{{$genre['id']}}"@if($genre['id'] == $generalGenre) {{" selected"}} @endif>{{$genre['value']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-heroes" role="tabpanel" aria-labelledby="v-pills-heroes-tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button onclick="GamesPage.unceck('#treeHeroescheckbox')" class="btn btn-primary btn-sm" type="button" data-original-title="" title=""><span class="icon-close"></span> Снять все</button>
                                                <hr>
                                                <label>Герои </label>
                                                {!! Form::hidden('heroes_add', '', ['id' => 'heroes_add']) !!}
                                                <div id="treeHeroescheckbox"></div>
                                            </div>
                                        </div>
                                    </div>
{{--                                    @endif--}}
                                    <div class="tab-pane fade" id="v-pills-command" role="tabpanel" aria-labelledby="v-pills-command-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h5>Первый игрок</h5>
                                                @if(!$translator)
                                                <button onclick="GamesControll.add(1)" class="btn btn-outline-info" type="button" data-original-title="Добавить" title=""><i class="icofont icofont-game-control"></i> Добавить</button>
                                                @endif
                                                    <hr/>
                                                <div id="rowCommandOne1" class="row-command row-command-one">

                                                </div>
                                            </div>
                                            <div class="col-md-6 section-command-2" style="display: none">
                                                <h5>Второй игрок</h5>
                                                @if(!$translator)
                                                <button onclick="GamesControll.add(2)" class="btn btn-outline-info" type="button" data-original-title="Добавить" title=""><i class="icofont icofont-game-control"></i> Добавить</button>
                                                @endif
                                                    <hr/>
                                                <div id="rowCommandOne2" class="row-command row-command-one">

                                                </div>
                                            </div>
                                            <div class="col-md-6 section-command-2" style="display: none">
                                                <h5>Третий игрок</h5>
                                                @if(!$translator)
                                                <button onclick="GamesControll.add(3)" class="btn btn-outline-info" type="button" data-original-title="Добавить" title=""><i class="icofont icofont-game-control"></i> Добавить</button>
                                                @endif
                                                    <hr/>
                                                <div id="rowCommandOne3" class="row-command row-command-one">

                                                </div>
                                            </div>
                                            <div class="col-md-6 section-command-2" style="display: none">
                                                <h5>Четвертый игрок</h5>
                                                @if(!$translator)
                                                <button onclick="GamesControll.add(4)" class="btn btn-outline-info" type="button" data-original-title="Добавить" title=""><i class="icofont icofont-game-control"></i> Добавить</button>
                                                @endif
                                                    <hr/>
                                                <div id="rowCommandOne4" class="row-command row-command-one">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if(!$translator)
                                    <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label" for="name">URL (алиас)</label>
                                                    {{ Form::text('url', old('url', $game->url), ['id' => 'url', 'class' => 'form-control']) }}
                                                    <small class="form-text text-muted" id="urlHelp">Оставьте поле пустым если хотите чтобы автоматически создался url</small>
                                                    @if ($errors->has('url'))
                                                        <div class="badge badge-danger">
                                                            <strong>{{ $errors->first('url') }}</strong>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <ul class="nav nav-tabs border-tab nav-primary" id="seo-tab" role="tablist">
                                                    @foreach(App\Models\Language::all() as $lang)
                                                        <li class="nav-item"><a class="nav-link {{ $lang->code == 'ru' ? 'active' : ''}}" id="seo-{{ $lang->code }}-tab" data-toggle="tab" href="#seo-{{ $lang->code }}" role="tab" aria-controls="seo-{{ $lang->code }}" aria-selected="{{ $lang->main == 1 ? 'true' : 'false'}}"><i class="flag-icon flag-icon-{{ $lang->code == 'en' ?'gb' : ($lang->code == 'uk' ? 'ua' : $lang->code) }}"></i></a></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="tab-content" id="info-tabContent">
                                                    @foreach(App\Models\Language::all() as $lang)
                                                        <div class="tab-pane fade {{ $lang->code == 'ru' ? 'show active' : ''}}" id="seo-{{ $lang->code }}" role="tabpanel" aria-labelledby="seo-{{ $lang->code }}-tab">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="seo_name_{{ $lang->code }}">СЕО Название </label>
                                                                        {{ Form::text('seo_name_' . $lang->code, old('seo_name_' . $lang->code, $langs[$lang->code]->seo_name), ['placeholder' => 'Введите сео название игры', 'id' => 'seo_name_' . $lang->code, 'class' => 'form-control' . ($translator && $lang->id == 1 ? ' nocopy' : ''), 're' . ($translator && $lang->id == 1 ? 'adonly' : '' ) => 'readonly']) }}
                                                                        @if ($errors->has('seo_name_' . $lang->code))
                                                                            <div class="badge badge-danger">
                                                                                <strong>{{ $errors->first('seo_name_' . $lang->code) }}</strong>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="seo_description_{{ $lang->code }}">СЕО писание </label>
                                                                        {{ Form::textarea('seo_description_' . $lang->code, old('seo_description_' . $lang->code, $langs[$lang->code]->seo_description), ['placeholder' => 'Введите сео описание игры', 'id' => 'seo_description_' . $lang->code, 'class' => 'form-control' . ($translator && $lang->id == 1 ? ' nocopy' : ''), 're' . ($translator && $lang->id == 1 ? 'adonly' : '' ) => 'readonly']) }}
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="v-pills-gallery" role="tabpanel" aria-labelledby="v-pills-gallery-tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group input-group-square">
                                                    <label for="video">Выберите видео </label>
                                                    <div class="input-group input-group-air input-group-video">
                                                        <label for="addVideoGameInput" class="btn btnaddimg btn-square btn-outline-primary m-b-15" type="button" data-original-title="@if(!empty($game->video)) Изменить видео: {{ $game->video }}@else Добавить видео @endif" title=""><i class="icon-plus"></i> @if(!empty($game->video)) Изменить видео: {{ $game->video }}@else Добавить видео @endif</label>
                                                        <input type="file" id="addVideoGameInput" accept="video/mp4" style="visibility:hidden;"  name="video" class="inpim" value="{{ old('video') }}">
                                                        <small class="form-text text-muted" id="urlHelp">Только формат mp4</small>
                                                    </div>
                                                    <input type="hidden" name="delete_video" value="{{ old('delete_video', 0) }}">
                                                    <button class="btn deletebtvid btn-square btn-outline-danger m-b-15" type="button"><i class="icon-trash"></i> Удалить видео</button>
                                                </div>
                                            </div>
                                            <div class="col-md-12 m-t-10">
                                                <h5><i class="icofont icofont-picture"></i> Фото игры</h5>
                                                <small class="gray-color">рекомендуемый размер изображения {{ config('site.game.image.width') }}х{{ config('site.game.image.height') }}px</small>
                                                @if ($errors->has('image'))
                                                    <div class="badge badge-danger">
                                                        <strong>Некорректный размер изображения</strong>
                                                    </div>
                                                @endif
                                                <div id="mmen" class="m-t-15 add-img-section">
                                                    <div>
                                                        <label for="addImgGameInput" class="btn btnaddimg btn-square btn-outline-primary m-b-15" type="button" data-original-title="@if(!empty($game->image)) Изменить @else Добавить @endifизображение" title=""><i class="icon-plus"></i> @if(!empty($game->image)) Изменить @else Добавить @endifизображение</label>
                                                        <input type="file" id="addImgGameInput" accept="image/*" style="visibility:hidden;"  name="image" class="inpim" value="{{ old('image') }}">
                                                    </div>
                                                    <input type="hidden" name="image_delete" class="delete-input" value="{{ old('image_delete', 0) }}">
                                                    <button class="btn deletebtimg btn-square btn-outline-danger m-b-15" @if(strlen($game->image) < 3) style="display: none" @endif type="button"><i class="icon-trash"></i> Удалить</button>

                                                    @if(!empty($game->image))
                                                        <div class="avatars">
                                                            <div class="avatar">
                                                                <img class="img-100" src="{{ old('image', !empty($game->image) ? Storage::disk('images')->url($game->image) : config('site.game.image.default')) }}" default="{{ config('site.game.image.default') }}" alt="#" data-original-title="" title="">
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-12 m-t-10">
                                                <hr>
                                                <h5><i class="icofont icofont-picture"></i> Фото каталог</h5>
                                                <small class="gray-color">рекомендуемый размер изображения {{ config('site.game.image_cat.width') }}х{{ config('site.game.image_cat.height') }}px</small>
                                                @if ($errors->has('image_cat'))
                                                    <div class="badge badge-danger">
                                                        <strong>Некорректный размер изображения</strong>
                                                    </div>
                                                @endif
                                                <div id="mmen_cat" class="m-t-15 add-img-section">
                                                    <div>
                                                        <label for="addImgCatGameInput" class="btn btnaddimg btn-square btn-outline-primary m-b-15" type="button" data-original-title="@if(!empty($game->image_cat)) Изменить @else Добавить @endifизображение" title=""><i class="icon-plus"></i> @if(!empty($game->image_cat)) Изменить @else Добавить @endifизображение</label>
                                                        <input type="file" id="addImgCatGameInput" accept="image/*" style="visibility:hidden;"  name="image_cat" class="inpim" value="{{ old('image_cat') }}">
                                                    </div>
                                                    <input type="hidden" name="image_cat_delete" class="delete-input" value="{{ old('image_cat_delete', 0) }}">
                                                    <button class="btn deletebtimg btn-square btn-outline-danger m-b-15" @if(strlen($game->image_cat) < 3) style="display: none" @endif type="button"><i class="icon-trash"></i> Удалить</button>
                                                    @if(!empty($game->image_cat))
                                                        <div class="avatars">
                                                            <div class="avatar">
                                                                <img class="img-100" src="{{ old('image_cat', !empty($game->image_cat) ? Storage::disk('images')->url($game->image_cat) : config('site.game.image_cat.default')) }}" default="{{ config('site.game.image_cat.default') }}" alt="#" data-original-title="" title="">
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-12 m-t-10">
                                                <div class="thumb">
                                                    <div class="thumb-inner">
                                                        <div class="thumb-link-wrap">
                                                            <a class="thumb-link" href="#">
                                                                <div class="cursor-layer"></div>
                                                                <div class="img-scaler">
                                                                    <div class="thumb-video-wrap">
                                                                        <video class="thumb-video" muted="" loop="" data-src="@if(!empty($game->video)){{ $game->video }}@endif"
                                                                               playsinline="">
                                                                        </video>
                                                                    </div>
                                                                    <img @if (!$game->mobi && isMobileDevice()) style="filter: grayscale(100%);" @endif
                                                                    class="img-scalable demilazyload"
                                                                         src="{{ $game->getMainImage() ?? '' }}"
                                                                         srcset="/images/watermark_300x300.png 100w"
                                                                         data-srcset="{{ $game->getImageByParams(\App\Options\ImageSizeOption::GAME_THUMB) ?? '' }} 100w"
                                                                         sizes="100vw"
                                                                         alt="{{ $game->getTitle(1) ?? '' }}">
                                                                    @if($game->isBest($category ?? null) || (!empty($best_game) && $game->best_game == 1))
                                                                        <div class="label-popular"><i class="icon-fire"></i></div>
                                                                    @endif
                                                                    @if($game->isUserView())
                                                                        <span class="label-played"><i class="icon-gamepad"></i></span>
                                                                    @endif
                                                                    @if($game->checkFavorite())
                                                                        <span class="label-fav"><i class="icon-star"></i></span>
                                                                    @endif
                                                                </div>
                                                                <div class="thumb-desc">{{ $game->getTitle(1) ?? '' }}</div>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <nav>
                                                    <ul class="nav nav-tabs border-tab nav-primary" id="iframe-tab" role="tablist">
                                                        <li class="nav-item"><a class="nav-link active" id="iframe-main-tab" data-toggle="tab" href="#iframe-main" role="tab" aria-controls="info-home" aria-selected="{{ $lang->main == 1 ? 'true' : 'false'}}">Основной Iframe</a></li>
                                                        @foreach(App\Models\Language::all() as $lang)
                                                            <li class="nav-item"><a class="nav-link" id="iframe-{{ $lang->code }}-tab" data-toggle="tab" href="#iframe-{{ $lang->code }}" role="tab" aria-controls="info-home" aria-selected="{{ $lang->main == 1 ? 'true' : 'false'}}"><i class="flag-icon flag-icon-{{ $lang->code == 'en' ? 'gb' : ($lang->code == 'uk' ? 'ua' : $lang->code) }}"></i></a></li>
                                                        @endforeach
                                                    </ul>
                                                </nav>
                                                <div class="tab-content" id="nav-tabContent">
                                                    @foreach(App\Models\Language::all() as $lang)
                                                        <div class="tab-pane fade show" id="iframe-{{$lang->code}}" role="tabpanel" aria-labelledby="iframe-ru-tab">
                                                            <label class="form-label" for="size_width">Iframe url </label>

                                                            {{ Form::textarea("iframe_url_$lang->code" , old('iframe_url', $langs[$lang->code]->iframe_url), ['id' => "iframe_url_$lang->code", 'class' => 'form-control']) }}
                                                            @if ($errors->has('iframe_url'))
                                                                <div class="badge badge-danger">
                                                                    <strong>{{ $errors->first('iframe_url') }}</strong>
                                                                </div>
                                                            @endif
                                                            <div class="form-group">
                                                                <label class="form-label" for="width">Ширина Iframe (оставьте пустым, чтобы использовать настройки по умолчанию)</label>
                                                                {!! Form::number("width_$lang->code", old('width', $langs[$lang->code]->width) , ['class' => 'form-control']) !!}
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label" for="height">Высота Iframe (оставьте пустым, чтобы использовать настройки по умолчанию)</label>
                                                                {!! Form::number("height_$lang->code", old('height', $langs[$lang->code]->height) , ['class' => 'form-control']) !!}
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label" for="size_width_{{$lang->code}}">Размер игры </label>
                                                                {!! Form::select("size_width_$lang->code", [ '0' => 'Узкая', '1' => 'Широкая' ] , old('size_width', $langs[$lang->code]->size_width) , ['class' => 'form-control']) !!}
                                                            </div>


                                                            <div class="form-group">
                                                                <label class="form-label" for="size_width">Url сайта </label>
                                                                {!! Form::text("url_site_$lang->code", old('url_site', $langs[$lang->code]->url_site) , ['class' => 'form-control']) !!}
                                                                @if ($errors->has('url_site'))
                                                                    <div class="badge badge-danger">
                                                                        <strong>{{ $errors->first('url_site') }}</strong>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label" for="size_width">Оригинальная ссылка на игру </label>
                                                                {!! Form::text("link_game_$lang->code", old('link_game', $langs[$lang->code]->link_game) , ['class' => 'form-control']) !!}
                                                                @if ($errors->has('link_game'))
                                                                    <div class="badge badge-danger">
                                                                        <strong>{{ $errors->first('link_game') }}</strong>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="media m-t-20">
                                                                        <div class="icon-state switch-outline">
                                                                            <label class="switch">
                                                                                {{ Form::checkbox("mobi_$lang->code",1, old('mobi', $langs[$lang->code]->mobi) == 1, ['id'=>"mobi_$lang->code"]) }}
                                                                                <span class="switch-state bg-success"></span>
                                                                            </label>
                                                                        </div>
                                                                        <label class="col-form-label m-l-10" for="mobi">Доступно на моб версии</label>
                                                                    </div>
                                                                </div>
                                                                @if(Auth::user()->isRoleAction('games_sandbox'))
                                                                    <div class="col-md-6">
                                                                        <div class="media m-t-20">
                                                                            <div class="icon-state switch-outline">
                                                                                <label class="switch">
                                                                                    {{ Form::checkbox("sandbox_$lang->code",1, old('sandbox', $langs[$lang->code]->sandbox) == 1, ['id'=>"sandbox_$lang->code"]) }}
                                                                                    <span class="switch-state bg-success"></span>
                                                                                </label>
                                                                            </div>
                                                                            <label class="col-form-label m-l-10" for="sandbox">Включить sandbox</label>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <input type="hidden" name="sandbox" value="1">
                                                                @endif
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="media m-t-20">
                                                                        <div class="icon-state switch-outline">
                                                                            <label class="switch">
                                                                                {{ Form::checkbox("target_blank_$lang->code",1, old('target_blank', $langs[$lang->code]->target_blank) == 1, ['id'=>"target_blank_$lang->code"]) }}
                                                                                <span class="switch-state bg-success"></span>
                                                                            </label>
                                                                        </div>
                                                                        <label class="col-form-label m-l-10" for="target_blank">Открывать в новой вкладке</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="media m-t-20">
                                                                        <div class="icon-state switch-outline">
                                                                            <label class="switch">
                                                                                {{ Form::checkbox("no_block_ad_$lang->code",1, old("no_block_ad_$lang->code", $langs[$lang->code]->no_block_ad) == 1, ['id'=>"no_block_ad_$lang->code"]) }}
                                                                                <span class="switch-state bg-success"></span>
                                                                            </label>
                                                                        </div>
                                                                        <label class="col-form-label m-l-10" for="target_blank">Не работает с блокировщиком рекламы</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    @endforeach
                                                    <div class="tab-pane fade show active" id="iframe-main" role="tabpanel" aria-labelledby="iframe-main-tab">
                                                        <label class="form-label" for="size_width">Iframe url</label>
                                                        {{ Form::textarea('iframe_url' , old('iframe_url', $game->iframe_url), ['id' => 'iframe_url', 'class' => 'form-control']) }}
                                                        @if ($errors->has('iframe_url'))
                                                            <div class="badge badge-danger">
                                                                <strong>{{ $errors->first('iframe_url') }}</strong>
                                                            </div>
                                                        @endif
                                                        <div class="form-group">
                                                            <label class="form-label" for="width">Ширина Iframe (оставьте пустым, чтобы использовать настройки по умолчанию)</label>
                                                            {!! Form::number('width', old('width', $game->width) , ['class' => 'form-control']) !!}
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label" for="height">Высота Iframe (оставьте пустым, чтобы использовать настройки по умолчанию)</label>
                                                            {!! Form::number('height', old('height', $game->height) , ['class' => 'form-control']) !!}
                                                        </div>

                                                          <div class="form-group" style="width:20%; display: inline-block">
                                                                <label class="form-label" for="height">Скрыть сверху, %</label>
                                                                {!! Form::number("iframe_offset_top", old('height', $game->iframe_offset_top) , ['class' => 'form-control']) !!}
                                                            </div>

                                                            <div class="form-group" style="width:20%; display: inline-block">
                                                                <label class="form-label" for="height">Скрыть справа, %</label>
                                                                {!! Form::number("iframe_offset_right", old('height', $game->iframe_offset_right) , ['class' => 'form-control']) !!}
                                                            </div>

                                                            <div class="form-group" style="width:20%; display: inline-block">
                                                                <label class="form-label" for="height">Скрыть снизу, %</label>
                                                                {!! Form::number("iframe_offset_bottom", old('height', $game->iframe_offset_bottom) , ['class' => 'form-control']) !!}
                                                            </div>

                                                            <div class="form-group" style="width:20%; display: inline-block">
                                                                <label class="form-label" for="height">Скрыть слева, %</label>
                                                                {!! Form::number("iframe_offset_left", old('height', $game->iframe_offset_left) , ['class' => 'form-control']) !!}
                                                            </div>

                                                        <div class="form-group">
                                                            <label class="form-label" for="size_width">Размер игры </label>
                                                            {!! Form::select('size_width', [ '0' => 'Узкая', '1' => 'Широкая' ] , old('size_width', $game->size_width) , ['class' => 'form-control']) !!}
                                                        </div>


                                                        <div class="form-group">
                                                            <label class="form-label" for="size_width">Url сайта </label>
                                                            {!! Form::text('url_site', old('url_site', $game->url_site) , ['class' => 'form-control']) !!}
                                                            @if ($errors->has('url_site'))
                                                                <div class="badge badge-danger">
                                                                    <strong>{{ $errors->first('url_site') }}</strong>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label" for="size_width">Оригинальная ссылка на игру </label>
                                                            {!! Form::text('link_game', old('link_game', $game->link_game) , ['class' => 'form-control']) !!}
                                                            @if ($errors->has('link_game'))
                                                                <div class="badge badge-danger">
                                                                    <strong>{{ $errors->first('link_game') }}</strong>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="media m-t-20">
                                                                    <div class="icon-state switch-outline">
                                                                        <label class="switch">
                                                                            {{ Form::checkbox('mobi',1, old('mobi', $game->mobi) == 1, ['id'=>'mobi']) }}
                                                                            <span class="switch-state bg-success"></span>
                                                                        </label>
                                                                    </div>
                                                                    <label class="col-form-label m-l-10" for="mobi">Доступно на моб версии</label>
                                                                </div>
                                                            </div>
                                                            <div class="media m-t-20 section-iphone-check" @if(old('mobi', $game->mobi) != 1)style="display: none"@endif>
                                                                <div class="icon-state switch-outline">
                                                                    <label class="switch">
                                                                        {{ Form::checkbox("iphone",1, old('iphone', $game->iphone) == 1, ['id'=>"iphone"]) }}
                                                                        <span class="switch-state bg-success"></span>
                                                                    </label>
                                                                </div>
                                                                <label class="col-form-label m-l-10" for="iphone">Не работает для iphone</label>
                                                            </div>
                                                            <div class="media m-t-20 section-horizontal-check" @if(old('mobi', $game->mobi) != 1)style="display: none"@endif>
                                                                <div class="icon-state switch-outline">
                                                                    <label class="switch">
                                                                        {{ Form::checkbox("horizontal",1, old('horizontal', $game->horizontal) == 1, ['id'=>"horizontal"]) }}
                                                                        <span class="switch-state bg-success"></span>
                                                                    </label>
                                                                </div>
                                                                <label class="col-form-label m-l-10" for="horizontal">Горизонтальная ориентация</label>
                                                            </div>
                                                            @if(Auth::user()->isRoleAction('games_sandbox'))
                                                                <div class="col-md-6">
                                                                    <div class="media m-t-20">
                                                                        <div class="icon-state switch-outline">
                                                                            <label class="switch">
                                                                                {{ Form::checkbox('sandbox',1, old('sandbox', $game->sandbox) == 1, ['id'=>'sandbox']) }}
                                                                                <span class="switch-state bg-success"></span>
                                                                            </label>
                                                                        </div>
                                                                        <label class="col-form-label m-l-10" for="sandbox">Включить sandbox</label>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <input type="hidden" name="sandbox" value="1">
                                                            @endif
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="media m-t-20">
                                                                    <div class="icon-state switch-outline">
                                                                        <label class="switch">
                                                                            {{ Form::checkbox('target_blank',1, old('target_blank', $game->target_blank) == 1, ['id'=>'target_blank']) }}
                                                                            <span class="switch-state bg-success"></span>
                                                                        </label>
                                                                    </div>
                                                                    <label class="col-form-label m-l-10" for="target_blank">Открывать в новой вкладке</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="media m-t-20">
                                                                    <div class="icon-state switch-outline">
                                                                        <label class="switch">
                                                                            {{ Form::checkbox('no_block_ad',1, old('no_block_ad', $game->no_block_ad) == 1, ['id'=>'no_block_ad']) }}
                                                                            <span class="switch-state bg-success"></span>
                                                                        </label>
                                                                    </div>
                                                                    <label class="col-form-label m-l-10" for="target_blank">Не работает с блокировщиком рекламы</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
{{--                                                <div class="form-group">--}}
{{--                                                    <label class="form-label" for="width">Ширина Iframe (оставьте пустым, чтобы использовать настройки по умолчанию)</label>--}}
{{--                                                    {!! Form::number('width', old('width', $game->width) , ['class' => 'form-control']) !!}--}}
{{--                                                </div>--}}
{{--                                                <div class="form-group">--}}
{{--                                                    <label class="form-label" for="height">Высота Iframe (оставьте пустым, чтобы использовать настройки по умолчанию)</label>--}}
{{--                                                    {!! Form::number('height', old('height', $game->height) , ['class' => 'form-control']) !!}--}}
{{--                                                </div>--}}
{{--                                                <div class="form-group">--}}
{{--                                                    <label class="form-label" for="size_width">Размер игры </label>--}}
{{--                                                    {!! Form::select('size_width', [ '0' => 'Узкая', '1' => 'Широкая' ] , old('size_width', $game->size_width) , ['class' => 'form-control']) !!}--}}
{{--                                                </div>--}}


{{--                                                <div class="form-group">--}}
{{--                                                    <label class="form-label" for="size_width">Url сайта </label>--}}
{{--                                                    {!! Form::text('url_site', old('url_site', $game->url_site) , ['class' => 'form-control']) !!}--}}
{{--                                                    @if ($errors->has('url_site'))--}}
{{--                                                        <div class="badge badge-danger">--}}
{{--                                                            <strong>{{ $errors->first('url_site') }}</strong>--}}
{{--                                                        </div>--}}
{{--                                                    @endif--}}
{{--                                                </div>--}}
{{--                                                <div class="form-group">--}}
{{--                                                    <label class="form-label" for="size_width">Оригинальная ссылка на игру </label>--}}
{{--                                                    {!! Form::text('link_game', old('link_game', $game->link_game) , ['class' => 'form-control']) !!}--}}
{{--                                                    @if ($errors->has('link_game'))--}}
{{--                                                        <div class="badge badge-danger">--}}
{{--                                                            <strong>{{ $errors->first('link_game') }}</strong>--}}
{{--                                                        </div>--}}
{{--                                                    @endif--}}
{{--                                                </div>--}}
                                            </div>
{{--                                            <div class="col-md-6">--}}
{{--                                                <div class="media m-t-20">--}}
{{--                                                    <div class="icon-state switch-outline">--}}
{{--                                                        <label class="switch">--}}
{{--                                                            {{ Form::checkbox('mobi',1, old('mobi', $game->mobi) == 1, ['id'=>'mobi']) }}--}}
{{--                                                            <span class="switch-state bg-success"></span>--}}
{{--                                                        </label>--}}
{{--                                                    </div>--}}
{{--                                                    <label class="col-form-label m-l-10" for="mobi">Доступно на моб версии</label>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            @if(Auth::user()->isRoleAction('games_sandbox'))--}}
{{--                                            <div class="col-md-6">--}}
{{--                                                <div class="media m-t-20">--}}
{{--                                                    <div class="icon-state switch-outline">--}}
{{--                                                        <label class="switch">--}}
{{--                                                            {{ Form::checkbox('sandbox',1, old('sandbox', $game->sandbox) == 1, ['id'=>'sandbox']) }}--}}
{{--                                                            <span class="switch-state bg-success"></span>--}}
{{--                                                        </label>--}}
{{--                                                    </div>--}}
{{--                                                    <label class="col-form-label m-l-10" for="sandbox">Включить sandbox</label>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            @else--}}
{{--                                                <input type="hidden" name="sandbox" value="1">--}}
{{--                                            @endif--}}
{{--                                            <div class="col-md-6">--}}
{{--                                                <div class="media m-t-20">--}}
{{--                                                    <div class="icon-state switch-outline">--}}
{{--                                                        <label class="switch">--}}
{{--                                                            {{ Form::checkbox('target_blank',1, old('target_blank', $game->target_blank) == 1, ['id'=>'target_blank']) }}--}}
{{--                                                            <span class="switch-state bg-success"></span>--}}
{{--                                                        </label>--}}
{{--                                                    </div>--}}
{{--                                                    <label class="col-form-label m-l-10" for="target_blank">Открывать в новой вкладке</label>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="col-md-6">--}}
{{--                                                <div class="media m-t-20">--}}
{{--                                                    <div class="icon-state switch-outline">--}}
{{--                                                        <label class="switch">--}}
{{--                                                            {{ Form::checkbox('no_block_ad',1, old('no_block_ad', $game->no_block_ad) == 1, ['id'=>'no_block_ad']) }}--}}
{{--                                                            <span class="switch-state bg-success"></span>--}}
{{--                                                        </label>--}}
{{--                                                    </div>--}}
{{--                                                    <label class="col-form-label m-l-10" for="target_blank">Не работает с блокировщиком рекламы</label>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="v-pills-similar" role="tabpanel" aria-labelledby="v-pills-similar-tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div id="custom-templates">
                                                            <div class="form-group">
                                                                <label class="form-label" for="similar">Игры из этой серии</label>
                                                                <input class="typeahead form-control" name="similar" type="text" placeholder="введите название игры">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="card">
{{--                                                    <div class="card-body">--}}
{{--                                                        <ul id="similar-templates" class="list-group">--}}
{{--                                                            @foreach($similars_game as $similar)--}}
{{--                                                                <li data-similar="{{ $similar->id }}" class="list-group-item d-flex justify-content-between align-items-center">--}}
{{--                                                                    <input type="hidden" name="similars[]" value="{{ $similar->id }}"> <div>--}}
{{--                                                                        <img class="rounded-circle img-40 mr-3 float-left" src="{{ $similar->getImage() }}">--}}
{{--                                                                        <span class="m-t-5 text-left">{{ $similar->name }}</span></div>--}}
{{--                                                                    <button onclick="$(this).parent().remove();" type="button" class="btn btn-light btn-lg">--}}
{{--                                                                        <span class="icon-close"></span>--}}
{{--                                                                    </button>--}}
{{--                                                                </li>--}}
{{--                                                            @endforeach--}}
{{--                                                        </ul>--}}
{{--                                                    </div>--}}
                                                    <div class="card-body">
                                                        <div id="similar-games-table" class="similar-games-table">
                                                            <h5 class="mt-6 text-center">Игры из этой серии</h5>
                                                            <table class="table table-bordered table-hover">
                                                                <tbody class="js-sorted-similar-games">
                                                                @if($similars_game)
                                                                    @php
                                                                        $flag = false;
                                                                        $count_similar = count($similars_game);
                                                                    @endphp
                                                                    @foreach($similars_game as $key => $similar)
                                                                        @if($similar->id == $game->id)
                                                                            @php $flag = true; @endphp
                                                                        @endif
                                                                        <tr>
                                                                            <td>
                                                                                <input class="number form-control" type="text"
                                                                                       name="similars[{{ $similar->position ?? ($key+1) }}][position]"
                                                                                       value="{{ $similar->position ?? ($key+1) }}" min="1"
                                                                                       style="width: 40px" readonly>
                                                                            </td>
                                                                            <td>
                                                                                <img class="b-r-8 img-50 pull-left m-r-10" src="https://img.gamesgo.net/storage/images/{{$similar->image_cat}}" alt="{{$similar->value}}" data-original-title="{{$similar->value}}" title="{{$similar->value}}">
                                                                                <strong class="m-t-5" style="display: block;">{{$similar->value}}</strong><small>{{$similar->value_en}}</small>
                                                                                <input type="hidden" class="game" name="similars[{{ $similar->position ?? ($key+1) }}][id]" value="{{$similar->id}}">
                                                                            </td>
                                                                            <td>
                                                                                @if($similar->id != $game->id)
                                                                                    <a href="javascript:void(0);"
                                                                                       onclick="$(this).closest('tr').remove();"
                                                                                       class="btn btn-light btn-lg"
                                                                                       style="font-size: 16px; display: inline-block; margin-left: 10px;">
                                                                                            <span class="icon-close"></span>
                                                                                    </a>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                        @if(!$flag && $count_similar == ($key+1))
                                                                            <tr>
                                                                                <td>
                                                                                    <input class="number form-control" type="text"
                                                                                           name="similars[{{ $key+2 }}][position]"
                                                                                           value="{{ $key+2 }}" min="1"
                                                                                           style="width: 40px" readonly>
                                                                                </td>
                                                                                <td>
                                                                                    <img class="b-r-8 img-50 pull-left m-r-10" src="https://img.gamesgo.net/storage/images/{{$game->image}}" alt="{{$game->name}}" data-original-title="{{$game->name}}" title="{{$game->name}}">
                                                                                    <strong class="m-t-5" style="display: block;">{{$game->name}}</strong><small>{{$game->name_en}}</small>
                                                                                    <input type="hidden" class="game" name="similars[{{ $key+2 }}][id]" value="{{$game->id}}">
                                                                                </td>
                                                                                <td>

                                                                                </td>
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach
                                                                @endif

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('m.games.index') }}" class="btn btn-dark">Закрыть</a>
                        <button class="btn btn-primary" name="save_exit" value="exit" type="submit">Сохраить и закрыть</button>
                        <button class="btn btn-info" name="save_exit" value="save" type="submit">Сохранить</button>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('stylesheet')
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/elfinder/js/jquery-ui-1.8.13.custom.css') }}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/elfinder/css/elfinder.min.css') }}">
    <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/elfinder/css/theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/select2/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/tree.css') }}">
    @if($translator)
        <script type="text/JavaScript">
            function killCopy(e){
                return false
            }
            function reEnable(){
                return true
            }
            document.onselectstart=new Function ("return false")
            if (window.sidebar){
                document.onmousedown=killCopy
                document.onclick=reEnable
            }
        </script>
    @endif
@endsection
@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="{{ asset('assets/elfinder/js/elfinder.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/elfinder/js/extras/editors.default.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/elfinder/js/i18n/elfinder.ru.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/tree/jstree.min.js') }}"></script>
    <script type="application/javascript">
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

        $("#addVideoGameInput").change(async function() {
            filename = this.files[0].name;
            const fileSize = getFileSize(this.files[0])
            const videoDuration = await getVideoDuration(this.files[0])
            const durationTime = videoDuration.duration ? ' . Длительность видео: ' + videoDuration.duration : ''
            $('label[for="addVideoGameInput"]').html('<i class="icon-plus"></i> Изменить видео: ' + filename
                + '. Размер видео: ' + fileSize + durationTime
            );
        });

        //addImgGameInput
        $("#addImgGameInput").change(function() {
            filename = this.files[0].name;
            $('label[for="addImgGameInput"]').html('<i class="icon-plus"></i> Изменить изображение: ' + filename);
        });

        $("#addImgCatGameInput").change(function() {
            filename = this.files[0].name;
            $('label[for="addImgCatGameInput"]').html('<i class="icon-plus"></i> Изменить изображение: ' + filename);
        });

        const getVideoDuration = async (file) => {
            const formData = new FormData()
            formData.append("inputFile", file)

            const result = await $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/games/videoduration",
                method: "post",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
            })

            return result
        }

        const getFileSize = file => {
            var _size = file.size;
            var fSExt = ['Bytes', 'KB', 'MB', 'GB'],
                i=0;while(_size>900){_size/=1024;i++;}
            var exactSize = (Math.round(_size*100)/100)+' '+fSExt[i];
            return exactSize
        }

        /*var addVideo = $('.input-group-video');

        addVideo.click(function() {
            $('<div />').dialogelfinder({
                url: '/assets/elfinder/php/connector.minimal_video.php',
                lang : 'ru',
                commandsOptions: {
                    getfile: {
                        onlyURL  : false,
                        oncomplete: 'destroy' // destroy elFinder after file selection
                    }
                },
                getFileCallback: function( file, fm ){
                    $('input[name="video"]').val(file['url']);
                }
            });
        });*/

        /*$('.btnaddimg').click(function() {
            var addImg = $(this).parents('.add-img-section');
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
                    addImg.find('.avatar img').attr('src', file['url']);
                    addImg.find('input.inpim').val(file['url']);
                    addImg.find('.deletebtimg').show();
                }
            });
        });*/

        $('.deletebtimg').on('click', function () {
            var addImgSec = $(this).parents('.add-img-section');
            var avatorImg = addImgSec.find('.avatar img');
            avatorImg.attr('src', avatorImg.attr('default'));
            addImgSec.find('input.inpim').val('');
            addImgSec.find('.deletebtimg').hide();
            addImgSec.find('input.delete-input').val(1);
        });

        $('.deletebtvid').on('click', function () {
            $('input[name="delete_video"]').val(1);
            $('label[for="addVideoGameInput"]').html('<i class="icon-plus"></i>  Добавить видео');
        });

        var controllSelect = {!! \App\Models\ButtonsPlayLang::where('lang_id', 1)->get(['buttons_play_id', 'name'])->toJson() !!};
        var controllLangs = {!! \App\Models\Language::get(['id', 'name'])->toJson() !!};
        var ControllTwoGame = {!! \App\Models\Genre::where('for_two', 1)->pluck('id') !!};
        var comand = {!! json_encode(old('control', $game->comand)) !!};

        var GamesControll = {
            init: function() {
                jQuery.each(comand, function(selectId, val) {
                    jQuery.each(val, function(dataId, com) {

                        var countSel = $('#rowCommandOne' + selectId).find('.p-relative').length + 1;

                        var outHtml = '<div data-id="' + dataId + '" class="itComRow p-10 m-b-10 shadow-sm shadow-showcase p-relative">';
                        outHtml += '<div class="ribbon ribbon-clip ribbon-primary"><i class="icofont icofont-hand-drag"></i> <span>' + countSel + '</span></div>';
                        outHtml += '<button type="button" onclick="GamesControll.delete(this)" class="ribbon btn btn-sm btn-secondary ribbon-right m-r-5"><i class="icofont icofont-ui-delete"></i></button>';
                        outHtml += '<div class="col-form-label p-l-50">Выбрать управление</div>';
                        outHtml += '<div class="csg comandSelectGroop' + selectId + '">';


                        $.each(com.input, function( indexinput, input ) {

                            outHtml +='<div class="comad-sel-item"><select name="comand[' + selectId + '][' + dataId + '][input][]" class="col-sm-10">';
                            $.each(controllSelect, function( index, value ) {
                                outHtml += '<option value="' + value.buttons_play_id  + '"' + (value.buttons_play_id == input ? 'selected' : '') + '>' + value.name + '</option>';
                            });
                            outHtml += '</select>';

                            if (indexinput == 0) {
                                outHtml += '<button onclick="GamesControll.addSelect(this, ' + selectId + ',' + dataId + ')" class="col-sm-2 btn btn-sm btn-outline-light btn-lg txt-dark" type="button"><i class="icofont icofont-ui-add"></i></button>';
                            } else {
                                outHtml+= '<button onclick="GamesControll.deleteSelect(this, ' + selectId + ',' + dataId + ')" class="col-sm-2 btn btn-sm btn-outline-secondary btn-lg txt-dark" type="button"><i class="icofont icofont-ui-delete"></i></button>';
                            }
                            outHtml += '</div>';
                        });



                        outHtml += '</div><div class="controll-new-name">';

                        if (typeof com.input !== 'undefined' && com.input.length > 1) {
                            $.each(controllLangs, function( index, value ) {
                                outHtml += '<div style="display: none" class="col-form-label">Название (' + value.name + ')</div>';
                                outHtml += '<input style="display: none" type="text" class="form-control col-sm-12 new-name-controll" name="comand[' + selectId + '][' + dataId + '][new-name][' + value.id + ']" value="' + (com['new-name'] && com['new-name'][value.id] ? com['new-name'][value.id] : '') + '"/>';
                            });
                        }

                        outHtml += '</div>';

                        $.each(controllLangs, function( index, value ) {
                            outHtml += '<div class="col-form-label">Описание (' + value.name + ')</div>';
                            outHtml += '<textarea class="form-control" rows="3" spellcheck="false" name="comand[' + selectId + '][' + dataId + '][textarea][' + value.id + ']">' + com.textarea[value.id] + '</textarea>';
                        });
                        outHtml += '</div>';

                        $('#rowCommandOne' + selectId).append(outHtml);

                    });
                    console.log('outHtml0:');
                });

                var vPillsCommandTab = 0;

                $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
                    //e.target // newly activated tab
                    //e.relatedTarget // previous active tab

                    if ($(e.target).attr('id') == 'v-pills-command-tab') {
                        GamesControll.selectActive();
                        vPillsCommandTab++;
                    }
                });

                var valSimilars = [];

                var bestPictures = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    prefetch: '{{ route('m.games.similar', ['game_id' => $game->id ?? 0]) }}',
                    remote: {
                        url: '{{ route('m.games.similar', ['game_id' => $game->id ?? 0]) }}?search=%QUERY&similars=' + valSimilars,
                        wildcard: '%QUERY'
                    }
                });

                var strSimilar = '<div class="media p-2"><img class="rounded-circle img-30 mr-3 float-left" src="https://img.gamesgo.net/storage/images/{#{image}#}">' +
                    '<div class="media-body align-self-center m-t-5">\n' +
                    '                                  <div>{#{value}#}</div>\n' +
                    '                                </div></div>';

                var similarTemplates = $('.js-sorted-similar-games');

                template = Handlebars.compile(strSimilar.replace(/}#/gi, '}').replace(/#{/gi, '{'));

                $('input[name="similar"]').typeahead(null, {
                    limit: 'Infinity',
                    name: 'best-pictures',
                    display: 'value',
                    highlight: true,
                    source: bestPictures,
                    templates: {
                        empty: [
                            '<div class="empty-message">',
                            'не найденно ни одной игры',
                            '</div>'
                        ].join('\n'),
                        suggestion: template
                    }
                }).on('typeahead:selected' , function(e,s,d) {
                    $('.js-sorted-similar-games tr').each(function (index){
                        valSimilars[index] = Number($(this).find('input.game').val());
                    });
                    //window.location = s.url;
                    if (s.id == {{ $game->id ?? 0 }}) {
                        $.notify({
                            title:'Ошибка',
                            message:'Вы не можете выбрать свою игру как похожую',
                        }, {
                            type:'danger',
                            mouse_over:true,
                        });
                    } else if (valSimilars.length == 0){
                        similarTemplates.append('<tr>' +
                                                    '<td>' +
                                                        '<input class="number form-control" type="text" name="similars[1][position]" value="1" min="1" style="width: 40px" readonly>' +
                                                    '</td>' +
                                                    '<td>' +
                                                        '<img class="b-r-8 img-50 pull-left m-r-10" src="https://img.gamesgo.net/storage/images/{{$game->image}}" alt="{{$game->name}}" data-original-title="{{$game->name}}" title="{{$game->name}}">' +
                                                        '<strong class="m-t-5" style="display: block;">{{$game->name}}</strong><small>{{$game->name_en}}</small>' +
                                                        '<input type="hidden" class="game" name="similars[1][id]" value="{{ $game->id }}">' +
                                                    '</td>' +
                                                    '<td>' +
                                                    '</td>' +
                                                '</tr>' +
                                                '<tr>' +
                                                    '<td>' +
                                                        '<input class="number form-control" type="text" name="similars[2][position]" value="2" min="1" style="width: 40px" readonly>' +
                                                    '</td>' +
                                                    '<td>' +
                                                        '<img class="b-r-8 img-50 pull-left m-r-10" src="https://img.gamesgo.net/storage/images/' + s.image + '" alt="' + s.value + '" data-original-title="' + s.value + '" title="' + s.value + '">' +
                                                        '<strong class="m-t-5" style="display: block;">' + s.value + '</strong><small>' + s.valueEn + '</small>' +
                                                        '<input type="hidden" class="game" name="similars[2][id]" value="' + s.id + '">' +
                                                    '</td>' +
                                                    '<td>' +
                                                        '<a href="javascript:void(0);" onclick="$(this).closest(\'tr\').remove();" class="btn btn-light btn-lg" style="font-size: 16px; display: inline-block; margin-left: 10px;">' +
                                                            '<span class="icon-close"></span>' +
                                                        '</a>' +
                                                    '</td>' +
                                                '</tr>');
                    } else if ($.inArray(s.id, valSimilars) < 0) {
                        var gamePosition = similarTemplates.find('input.number').length + 1;
                        similarTemplates.append('<tr>' +
                                                    '<td>' +
                                                        '<input class="number form-control" type="text" name="similars[' + gamePosition + '][position]" value="' + gamePosition + '" min="1" style="width: 40px" readonly>' +
                                                    '</td>' +
                                                    '<td>' +
                                                        '<img class="b-r-8 img-50 pull-left m-r-10" src="https://img.gamesgo.net/storage/images/' + s.image + '" alt="' + s.value + '" data-original-title="' + s.value + '" title="' + s.value + '">' +
                                                        '<strong class="m-t-5" style="display: block;">' + s.value + '</strong><small>' + s.valueEn + '</small>' +
                                                        '<input type="hidden" class="game" name="similars[' + gamePosition + '][id]" value="' + s.id + '">' +
                                                    '</td>' +
                                                    '<td>' +
                                                        '<a href="javascript:void(0);" onclick="$(this).closest(\'tr\').remove();" class="btn btn-light btn-lg" style="font-size: 16px; display: inline-block; margin-left: 10px;">' +
                                                            '<span class="icon-close"></span>' +
                                                        '</a>' +
                                                    '</td>' +
                                                '</tr>');
                        valSimilars = [];
                        /*similarTemplates.find('input[name="similars[]"]').each(function( index ) {
                            valSimilars.push($( this ).val());
                        });*/
                    } else {
                        $.notify({
                            title:'Ошибка',
                            message:'Такая игра уже есть',
                        }, {
                            type:'danger',
                            mouse_over:true,
                        });
                    }

                    $('input[name="similar"]').typeahead('val', '');
                });

                $('#rowCommandOne1').sortable({
                    update: function(event, ui) {GamesControll.sort($('#rowCommandOne1'));}
                });

                $('#rowCommandOne2').sortable({
                    update: function(event, ui) {GamesControll.sort($('#rowCommandOne2'));}
                });

                $('#rowCommandOne3').sortable({
                    update: function(event, ui) {GamesControll.sort($('#rowCommandOne3'));}
                });

                $('#rowCommandOne4').sortable({
                    update: function(event, ui) {GamesControll.sort($('#rowCommandOne4'));}
                });


            },
            selectActive: function() {
                if ($('#rowCommandOne1').find('select').length) {
                    $('#rowCommandOne1').find('select').select2({
                        language: "ru",
                    });
                }

                if ($('#rowCommandOne2').find('select').length) {
                    $('#rowCommandOne2').find('select').select2({
                        language: "ru",
                    });
                }

                if ($('#rowCommandOne3').find('select').length) {
                    $('#rowCommandOne3').find('select').select2({
                        language: "ru",
                    });
                }

                if ($('#rowCommandOne4').find('select').length) {
                    $('#rowCommandOne4').find('select').select2({
                        language: "ru",
                    });
                }
            },
            add: function (selectId) {
                var countSel = $('#rowCommandOne' + selectId).find('.p-relative').length + 1;
                var d = new Date();
                var dataId = d.getFullYear() + '' + d.getMonth() + '' + d.getDay() + '' + d.getHours() + '' + d.getMinutes() + '' + d.getMilliseconds();
                var outHtml = '<div data-id="' + dataId + '" class="itComRow p-10 m-b-10 shadow-sm shadow-showcase p-relative">';
                outHtml += '<div class="ribbon ribbon-clip ribbon-primary"><i class="icofont icofont-hand-drag"></i> <span>' + countSel + '</span></div>';
                outHtml += '<button type="button" onclick="GamesControll.delete(this)" class="ribbon btn btn-sm btn-secondary ribbon-right m-r-5"><i class="icofont icofont-ui-delete"></i></button>';
                outHtml += '<div class="col-form-label p-l-50">Выбрать управление</div>';
                outHtml += '<div class="csg comandSelectGroop' + selectId + '">';

                outHtml +='<div class="comad-sel-item"><select name="comand[' + selectId + '][' + dataId + '][input][]" class="col-sm-10">';
                $.each(controllSelect, function( index, value ) {
                    outHtml += '<option value="' + value.buttons_play_id  + '">' + value.name + '</option>';
                });
                outHtml += '</select><button onclick="GamesControll.addSelect(this, ' + selectId + ',' + dataId + ')" class="col-sm-2 btn btn-outline-light btn-lg txt-dark" type="button"><i class="icofont icofont-ui-add"></i></button></div>';

                outHtml += '</div><div class="controll-new-name"></div>';
                $.each(controllLangs, function( index, value ) {
                    outHtml += '<div class="col-form-label">Описание (' + value.name + ')</div>';
                    outHtml += '<textarea class="form-control" rows="3" spellcheck="false" name="comand[' + selectId + '][' + dataId + '][textarea][' + value.id + ']"></textarea>';
                });
                outHtml += '</div>';

                $('#rowCommandOne' + selectId).append(outHtml);
                $('#rowCommandOne' + selectId).find('select').select2({
                    language: "ru",
                });
            },
                addSelect: function (thisSel, selectId, dataId) {
                    var csg = $(thisSel).parents('.csg');
                    var sel = '<div class="comad-sel-item m-t-5"> <select name="comand[' + selectId + '][' + dataId + '][input][]" class="col-sm-10">';
                    $.each(controllSelect, function( index, value ) {
                        sel += '<option value="' + value.buttons_play_id  + '">' + value.name + '</option>';
                    });
                    sel += '</select><button onclick="GamesControll.deleteSelect(this, ' + selectId + ',' + dataId + ')" class="col-sm-2 btn btn-sm btn-outline-secondary btn-lg txt-dark" type="button"><i class="icofont icofont-ui-delete"></i></button></div>';

                    csg.append(sel);
                    $('#rowCommandOne' + selectId).find('select').select2({
                        language: "ru",
                    });
                    GamesControll.checkNewInput(selectId, dataId);
                },
                deleteSelect: function (thisSel, selectId, dataId) {
                    $(thisSel).parents('.comad-sel-item').remove();
                    GamesControll.checkNewInput(selectId, dataId);
                },
                checkNewInput: function (selectId, dataId) {
                    var selectLeght = $('#rowCommandOne' + selectId).find('select').length;
                    var inputname = $('#rowCommandOne' + selectId).find('input.new-name-controll');

                    if(selectLeght > 1 && inputname.length < 1) {
                        // Add Input name
                        var newInput = '';
                        $.each(controllLangs, function( index, value ) {
                            newInput += '<div style="display: none" class="col-form-label">Название (' + value.name + ')</div>';
                            newInput += '<input style="display: none" type="text" class="form-control col-sm-12 new-name-controll" name="comand[' + selectId + '][' + dataId + '][new-name][' + value.id + ']">';
                        });
                        $('#rowCommandOne' + selectId).find('.controll-new-name').html(newInput);
                    } else if (selectLeght < 2 && inputname.length > 0) {
                        // Delete Input name
                        $('#rowCommandOne' + selectId).find('.controll-new-name').html('');
                    }

                },
                delete: function (thisBtn) {
                    var gn = $(thisBtn).parents('.row-command');
                    $(thisBtn).parents('.itComRow').remove();
                    GamesControll.sort(gn);
                },
                sort: function (divTag) {
                    var itComRow = divTag.find('.itComRow');
                    if (itComRow.length > 0) {
                        itComRow.each(function( index ) {
                            $( this ).find('.ribbon-primary > span').text(index+1);
                        });
                    }
                }
        };

        var GamesPage = {
            init: function () {

                $('#genre_id').select2({
                    language: "ru",
                });

                $('#mobi:checkbox').change(function() {
                    // this will contain a reference to the checkbox
                    if (this.checked) {
                        $('.section-iphone-check').show();
                        $('.section-horizontal-check').show();
                    } else {
                        $('.section-iphone-check').hide();
                        $('.section-horizontal-check').hide();
                    }
                });

                var tree = $('#treecheckbox');

                tree.jstree({
                    "checkbox": { "three_state": false },
                    'core': {
                        'data': {!! $tree !!}
                    },
                    "plugins": ["checkbox"],
                    'tie_selection': false
                });

                tree.on('changed.jstree', function (e, data) {

                    if (typeof data.node !== 'undefined') {
                        if(data.node.id != 'undefined' && jQuery.inArray(parseInt(data.node.id), ControllTwoGame) !== -1){
                            if (data.action == "select_node") {
                                $('.section-command-2').show();
                            } else {
                                $('.section-command-2').hide();
                            }
                        }
                    }

                    $('input#genre_add').val(data.selected.join(","));
                });

                tree.on('loaded.jstree', function (e, data) {
                    seltree = '{{ old('genre_add', $game->genre_add) }}';
                    seltreearray = seltree.split(',');
                    seltreearray.forEach(function(entry) {
                        tree.jstree('select_node', parseInt(entry));
                    });
                });

                // Heroes
                var treeh = $('#treeHeroescheckbox');
                treeh.jstree({
                    "checkbox": { "three_state": false },
                    'core': {
                        'data': {!! $heroes_tree !!}
                    },
                    "plugins": ["checkbox"],
                    'tie_selection': false
                }).on('changed.jstree', function (e, data) {
                    $('input#heroes_add').val(data.selected.join(","));
                });

                treeh.on('loaded.jstree', function (e, data) {
                    seltreeh = '{{ old('heroes_add', $game->heroes_add) }}';
                    seltreeharray = seltreeh.split(',');
                    seltreeharray.forEach(function(entry) {
                        treeh.jstree('select_node', parseInt(entry));
                    });
                });

                var bestDevelopers = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    prefetch: '{{ route('m.games.api.developer.get') }}',
                    remote: {
                        url: '{{ route('m.games.api.developer.get') }}?q=%QUERY',
                        wildcard: '%QUERY'
                    }
                });

                $('#developer_name').typeahead({
                    minLength: 0,
                    highlight: true
                }, {
                    name: 'developer_name',
                    limit: 20,
                    display: 'name',
                    source: bestDevelopers
                });
            },
            unceck: function (checkid) {
                $(checkid).jstree(true).deselect_all();
            }
        };

        function limitChars(myObject, max, typeChars, leftChars){
            $(myObject).on("change keyup paste", function(){
                showNum(myObject, max, typeChars, leftChars);
            });
        }

        function showNum(myObject, max, typeChars, leftChars) {
            var count = $(myObject).val().length; // кол-во уже введенных символов
            var num = max - count; // кол-во символов, которое еще можно ввести

            if(num > 0){
                // если не достигнут лимит символов
                $(typeChars).text('Символов с пробелом: ' + count);
                $(leftChars).text('Можно ввести символов: ' + num);
                $(myObject).removeClass('type');
            }else{
                // если достигнут лимит символов
                $(typeChars).text('Символов с пробелом: ' + count);
                $(leftChars).text('Достигнут лимит символов');
                $(myObject).addClass('type');
            }
        }

        $(function () {
            'use strict';
            //jQuery code here
            GamesPage.init();
            GamesControll.init();

            @foreach(App\Models\Language::all() as $lang)
                // Description
                var myObject{{ $lang->code }} = '#description_{{ $lang->code }}'; // объект, у которого считаем символы
                var max{{ $lang->code }} = 1000; // максимум символов
                var typeChars{{ $lang->code }} = '.ssp_{{ $lang->code }}'; // куда выводим кол-во введенных символов
                //var leftChars = '#leftChars'; // куда выводим кол-во оставшихся символов
                limitChars(myObject{{ $lang->code }}, max{{ $lang->code }}, typeChars{{ $lang->code }}, null);

                if($(myObject{{ $lang->code }}).val().length > 0) {
                    showNum(myObject{{ $lang->code }}, max{{ $lang->code }}, typeChars{{ $lang->code }}, null);
                }

                // how_play_
                var myObjectk{{ $lang->code }} = '#how_play_{{ $lang->code }}'; // объект, у которого считаем символы
                var maxk{{ $lang->code }} = 1000; // максимум символов
                var typeCharsk{{ $lang->code }} = '.ssk_{{ $lang->code }}'; // куда выводим кол-во введенных символов
                //var leftCharsk = '#leftCharsk'; // куда выводим кол-во оставшихся символов
                limitChars(myObjectk{{ $lang->code }}, maxk{{ $lang->code }}, typeCharsk{{ $lang->code }}, null);

                if($(myObjectk{{ $lang->code }}).val().length > 0) {
                    showNum(myObjectk{{ $lang->code }}, maxk{{ $lang->code }}, typeCharsk{{ $lang->code }}, null);
                }

            @endforeach


        });

        // sorting similar games

        initSortingSimilarGames();

        function initSortingSimilarGames() {
            $( 'tbody.js-sorted-similar-games' ).sortable({
                stop: function( ) {
                    numerationUpdateSimilarGames();
                }
            });
        }

        function numerationUpdateSimilarGames() {
            $('.js-sorted-similar-games').each(function (){
                var rows = $(this).find('input.number');
                var rowsQuant = $(this).find('input.number').length;
                var game = $(this).find('input.game');

                for (var i = 0; i < rowsQuant; i++) {
                    $(rows[i]).val(Number(i)+ Number(1));
                    let game_name = 'similars[' + (Number(i) + Number(1)) + '][id]';
                    let pos_name = 'similars[' + (Number(i) + Number(1)) + '][position]';
                    $(rows[i]).attr('name', pos_name);
                    $(game[i]).attr('name', game_name);
                }
            });
        }

        // sorting similar games
        $('#general_category').select2({
            placeholder: 'Select an option',
            width: '100%',
        });
    </script>
    <style>
        .tt-menu {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
@endsection