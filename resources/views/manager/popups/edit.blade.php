@extends('manager.app')
@section('title', 'Редакторование  Pop Up окона')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3><i data-feather="layout"></i> Редакторование  Pop Up окона</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item active">Редакторование  Pop Up окона</li>
                    </ol>
                </div>
                <div class="col-lg-6">
                    <div class="pull-right">
                        <ul>
                            <li><a class="btn btn-dark" href="{{ route('m.popups.index') }}" data-container="body" data-toggle="popover" data-placement="top" title="" data-original-title="Все жанры "> Назад</a></li>
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
                    {{ Form::open(['class' => 'novalidate', 'id' => 'formPopoupsEdit', 'method' => 'put']) }}
                    <div class="card-body">
                        <div class="row">
                            {!! Form::hidden('_token', csrf_token()) !!}
                            {!! Form::hidden('id', $popup->id) !!}
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
                                                        <label class="form-label" for="name_{{ $lang->code }}">Название PopUp окна {!! $lang->main == 1 ? '<span class="text-danger">*</span>' : '' !!}</label>
                                                        {{ Form::text('name_' . $lang->code, old('name_' . $lang->code, $langs[$lang->code]->name), ['placeholder' => 'Введите название PopUp окна', 'id' => 'name_' . $lang->code, 'class' => 'form-control', 'requi' . ($lang->main == 1 ? 'red' : '') => 'required']) }}
                                                        @if ($errors->has('name_' . $lang->code))
                                                            <div class="badge badge-danger">
                                                                <strong>{{ $errors->first('name_' . $lang->code) }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label" for="text_{{ $lang->code }}">Описание </label>
                                                        {{ Form::textarea('text_' . $lang->code, old('text_' . $lang->code, $langs[$lang->code]->text), ['placeholder' => 'Введите вверхнее описание PopUp окна', 'id' => 'text_' . $lang->code, 'class' => 'form-control ckeditor']) }}
                                                        @if ($errors->has('text_' . $lang->code))
                                                            <div class="badge badge-danger">
                                                                <strong>{{ $errors->first('text_' . $lang->code) }}</strong>
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
                    <div class="card-footer text-right">
                        <a href="{{ route('m.popups.index') }}" class="btn btn-dark">Закрыть</a>
                        <button class="btn btn-primary" type="submit">Сохраить</button>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('stylesheet')
@endsection
@section('script')
    <script src="{{ asset('assets/js/editor/ckeditor5/ckeditor.js') }}"></script>
    <script type="application/javascript">
        // Default ckeditor
        @foreach(App\Models\Language::all() as $lang)
        ClassicEditor
            .create( document.querySelector( '#text_{{ $lang->code }}' ) )
            .catch( error => {
                console.error( error );
            } );
        @endforeach
    </script>
@endsection