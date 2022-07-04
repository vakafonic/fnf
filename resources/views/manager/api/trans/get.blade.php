<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="myLangModalLabel"></h4>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        {{ Form::open(['class' => 'novalidate', 'id' => 'formTransEdit']) }}
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">@include('manager.notifications')</div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="id" value="{{ $trans->id }}">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label" for="key_lang"> Ключь (алиас) <span class="text-danger">*</span></label>
                        {{ $readonly = !empty($trans->id) ? 'readonly' : 'read' }}
                        {{ Form::text('key_lang', old('key_lang', $trans->key_lang), ['placeholder' => 'Ключь', 'id' => 'key_lang', 'class' => 'form-control', 'maxlength' => 255, $readonly => $readonly ]) }}
                        @if ($errors->has('key_lang'))
                            <div class="badge badge-danger">
                                <strong>{{ $errors->first('key_lang') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
                @foreach ($langs as $code => $lang)
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="name"> {{ $lang['name'] }}
                                @if ($lang['main'] == 1)
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                            {{ Form::text($code, old($code, $lang['value']), ['placeholder' => '', 'id' => $code, 'class' => 'form-control', 'maxlength' => 255]) }}
                            @if ($errors->has($code))
                                <div class="badge badge-danger">
                                    <strong>{{ $errors->first($code) }}</strong>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="card-footer text-right">
            <button class="btn btn-primary" type="button" data-dismiss="modal" data-original-title="" title="">Закрыть</button>
            <button class="btn btn-success" onclick="AdminTransPage.saveTrans()" type="button">Сохранить</button>
        </div>
        {{ Form::close() }}
    </div>
</div>