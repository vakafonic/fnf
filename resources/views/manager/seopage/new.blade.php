@extends('manager.app')
@section('title', !empty($seopage->id) ? 'Редактировать СЕО страницу' : 'Создать новую СЕО страницу')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3><i class="icofont icofont-math"></i> {{ !empty($seopage->id) ? 'Редактировать СЕО страницу' : 'Создать новую СЕО страницу' }}</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('m.seopage.index') }}">Все СЕО страницы</a></li>
                        <li class="breadcrumb-item active">{{ !empty($seopage->id) ? 'Редактировать СЕО страницу' : 'Создать новую СЕО страницу' }}</li>
                    </ol>
                </div>
                <div class="col-lg-6">
                    <div class="pull-right">
                        <ul>
                            <li><a class="btn btn-dark" href="{{ route('m.seopage.index') }}" data-container="body" data-toggle="popover" data-placement="top" title="" data-original-title="Все жанры ">Все СЕО страницы</a></li>
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
                {{ Form::open(['class' => 'novalidate', 'id' => 'formSeoEdit', 'method' => 'put']) }}
                <div class="card-body">
                    <div class="row">
                        {!! Form::hidden('_token', csrf_token()) !!}
                        {!! Form::hidden('id', $seopage->id) !!}
                        <div class="col-md-12">
                            <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="top-main-tab" data-toggle="tab" href="#top-main" role="tab" aria-controls="top-main" aria-selected="true"><i class="icofont icofont-ui-home"></i>Основное</a></li>
                                <li class="nav-item"><a class="nav-link" id="top-menucat-tab" data-toggle="tab" href="#top-menucat" role="tab" aria-controls="top-menucat" aria-selected="false"><i class="icofont icofont-direction-sign"></i>Категори</a></li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="tab-content" id="top-tabContent">
                                <div class="tab-pane fade show active" id="top-main" role="tabpanel" aria-labelledby="top-main-tab">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5><i class="icofont icofont-repair"></i> Основное</h5>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="url"> Ключь (алиас)</label>
                                                        {{ Form::text('url', old('url', $seopage->url), ['placeholder' => 'URL (алиас)', 'id' => 'url', 'class' => 'form-control', 'maxlength' => 255 ]) }}
                                                        <small class="form-text text-muted" id="urlHelp">Оставьте поле пустым если хотите чтобы автоматически создался url</small>
                                                        @if ($errors->has('url'))
                                                            <div class="badge badge-danger">
                                                                <strong>{{ $errors->first('url') }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="form-label" for="sort"> Сортировка</label>
                                                        {{ Form::number('sort', old('sort', $seopage->sort ?? 0), ['placeholder' => 'сортировка', 'id' => 'sort', 'class' => 'form-control', 'maxlength' => 255]) }}
                                                        @if ($errors->has('sort'))
                                                            <div class="badge badge-danger">
                                                                <strong>{{ $errors->first('sort') }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="media m-t-20">
                                                        <label class="col-form-label m-r-10" for="public">Опубликован</label>
                                                        <div class="media-body icon-state switch-outline">
                                                            <label class="switch">
                                                                {{ Form::checkbox('public',1, old('public', $seopage->public == 1 ?? 0) == 1, ['id'=>'public']) }}
                                                                <span class="switch-state bg-success"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="comment"> Комментарий к страницу (не выводится нигде)</label>
                                                        {{ Form::textarea('comment', old('comment', $seopage->comment), ['placeholder' => 'Комментарий или подсказка', 'id' => 'comment', 'class' => 'form-control']) }}
                                                        @if ($errors->has('comment'))
                                                            <div class="badge badge-danger">
                                                                <strong>{{ $errors->first('comment') }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
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
                                                                    <label class="form-label" for="value_{{ $lang->code }}">Название {!! $lang->main == 1 ? '<span class="text-danger">*</span>' : '' !!}</label>
                                                                    {{ Form::text('value_' . $lang->code, old('value_' . $lang->code, $langs[$lang->code]->value), ['placeholder' => 'Введите название', 'id' => 'value_' . $lang->code, 'class' => 'form-control', 'requi' . ($lang->main == 1 ? 'red' : '') => 'required']) }}
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
                                                                    {{ Form::textarea('description_' . $lang->code, old('description_' . $lang->code, $langs[$lang->code]->description), ['placeholder' => 'Введите описание', 'id' => 'description_' . $lang->code, 'class' => 'form-control']) }}
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
                                                                    {{ Form::textarea('description_buttom_' . $lang->code, old('description_buttom_' . $lang->code, $langs[$lang->code]->description_buttom), ['placeholder' => 'Введите описание отображающей в футоре', 'id' => 'description_buttom_' . $lang->code, 'class' => 'form-control']) }}
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
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="top-menucat" role="tabpanel" aria-labelledby="top-menucat-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div id="custom-templates">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="similar">Жанры</label>
                                                                    <input class="typeahead form-control" id="categories" type="text" placeholder="введите название жанра">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <p>Для сортироваки, просто перетяните строку в нужную позицию</p>
                                                            <ul id="categories-templates" class="list-group">
                                                                @foreach($seopage->getGenries(1) as $category)
                                                                    <li data-genry="{{ $category->id }}" class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <input type="hidden" name="genries[]" value="{{ $category->id }}"> <div>
                                                                            <img class="rounded-circle img-40 mr-3 float-left" src="{{ $category->getUrlImage() }}">
                                                                            <span class="m-t-5 text-left">{{ $category->value }}</span></div>
                                                                        <button onclick="$(this).parent().remove();" type="button" class="btn btn-light btn-lg">
                                                                            <span class="icon-close"></span>
                                                                        </button>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div id="custom-templates-heroes">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="heroes">Герои</label>
                                                                    <input class="typeahead form-control" id="heroes" type="text" placeholder="введите название героя">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <p>Для сортироваки, просто перетяните строку в нужную позицию</p>
                                                            <ul id="heroes-templates" class="list-group">
                                                                @foreach($seopage->getHeroes(1) as $category)
                                                                    <li data-heroy="{{ $category->id }}" class="list-group-item d-flex justify-content-between align-items-center">
                                                                        <input type="hidden" name="heroes[]" value="{{ $category->id }}"> <div>
                                                                            <img class="rounded-circle img-40 mr-3 float-left" src="{{ $category->getUrlImage() }}">
                                                                            <span class="m-t-5 text-left">{{ $category->value }}</span></div>
                                                                        <button onclick="$(this).parent().remove();" type="button" class="btn btn-light btn-lg">
                                                                            <span class="icon-close"></span>
                                                                        </button>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{ route('m.seopage.index') }}" class="btn btn-dark">Закрыть</a>
                    <button class="btn btn-primary" type="submit">Сохранить</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection
@section('stylesheet')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/select2/css/select2.min.css') }}">
@endsection
@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
    <script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/js/i18n/ru.js') }}"></script>
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

        // Genries

        $( "#categories-templates" ).sortable();
        $( "#categories-templates" ).disableSelection();

        var bestPictures = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: '{{ route('m.seopage.genries') }}?search=%QUERY',
                wildcard: '%QUERY'
            }
        });

        var strCategory = '<div class="media p-2"><img class="rounded-circle img-30 mr-3 float-left" src="{#{image}#}">' +
            '<div class="media-body align-self-center m-t-5">\n' +
            '                                  <div>{#{value}#}</div>\n' +
            '                                </div></div>';

        var categoriesTemplates = $('#categories-templates');

        template = Handlebars.compile(strCategory.replace(/}#/gi, '}').replace(/#{/gi, '{'));

        $('input#categories').typeahead(null, {
            limit: 10,
            name: 'best-pictures',
            display: 'value',
            highlight: true,
            source: bestPictures,
            templates: {
                empty: [
                    '<div class="empty-message">',
                    'не найденно ни одного жанра',
                    '</div>'
                ].join('\n'),
                suggestion: template
            }
        }).on('typeahead:selected' , function(e,s,d) {
            //window.location = s.url;
            if (!categoriesTemplates.find('li[data-category="' + s.id + '"]').length) {
                categoriesTemplates.append('<li data-genry="' + s.id + '" class="list-group-item d-flex justify-content-between align-items-center">' +
                    '<input type="hidden" name="genries[]" value="' + s.id + '"> <div>' +
                    '<img class="rounded-circle img-40 mr-3 float-left" src="' + s.image + '">' +
                    '<span class="m-t-5 text-left">' + s.value + '</span></div>' +
                    '<button onclick="$(this).parent().remove();" type="button" class="btn btn-light btn-lg">' +
                    '<span class="icon-close"></span></button></li>');
            } else {
                $.notify({
                    title:'Ошибка',
                    message:'Такой жанр уже есть',
                }, {
                    type:'danger',
                    mouse_over:true,
                });
            }

            $('input#categories').typeahead('val', '');
        });

        // Heroes

        $( "#heroes-templates" ).sortable();
        $( "#heroes-templates" ).disableSelection();

        var bestHeroes = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: '{{ route('m.seopage.heroes') }}?search=%QUERY',
                wildcard: '%QUERY'
            }
        });

        var heroesTemplates = $('#heroes-templates');

        $('input#heroes').typeahead(null, {
            limit: 10,
            name: 'best-heroes',
            display: 'value',
            highlight: true,
            source: bestHeroes,
            templates: {
                empty: [
                    '<div class="empty-message">',
                    'не найденно ни одного героя',
                    '</div>'
                ].join('\n'),
                suggestion: template
            }
        }).on('typeahead:selected' , function(e,s,d) {
            //window.location = s.url;
            if (!heroesTemplates.find('li[data-heroy="' + s.id + '"]').length) {
                heroesTemplates.append('<li data-heroy="' + s.id + '" class="list-group-item d-flex justify-content-between align-items-center">' +
                    '<input type="hidden" name="heroes[]" value="' + s.id + '"> <div>' +
                    '<img class="rounded-circle img-40 mr-3 float-left" src="' + s.image + '">' +
                    '<span class="m-t-5 text-left">' + s.value + '</span></div>' +
                    '<button onclick="$(this).parent().remove();" type="button" class="btn btn-light btn-lg">' +
                    '<span class="icon-close"></span></button></li>');
            } else {
                $.notify({
                    title:'Ошибка',
                    message:'Такой герой уже есть',
                }, {
                    type:'danger',
                    mouse_over:true,
                });
            }

            $('input#heroes').typeahead('val', '');
        });

    </script>
@endsection