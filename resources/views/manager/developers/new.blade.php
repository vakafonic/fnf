<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header"> 
            <h4 class="modal-title" id="myLangModalLabel">{{ !empty($developer->id) ? 'Редактировать' : 'Создать ' }} разработчика </h4>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
        {{ Form::open(['class' => 'novalidate', 'id' => 'formDeveloperEdit']) }}
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">@include('manager.notifications')</div>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="id" value="{{ $developer->id }}">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label" for="url"> Ключь (алиас)</label>
                        {{ Form::text('url', old('url', $developer->url), ['placeholder' => 'URL (алиас)', 'id' => 'url', 'class' => 'form-control', 'maxlength' => 255 ]) }}
                        <small class="form-text text-muted" id="urlHelp">Оставьте поле пустым если хотите чтобы автоматически создался url</small>
                        @if ($errors->has('url'))
                            <div class="badge badge-danger">
                                <strong>{{ $errors->first('url') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="form-label" for="name"> Название</label>
                        {{ Form::text('name', old('name', $developer->name), ['placeholder' => 'название разработчика', 'id' => 'name', 'class' => 'form-control', 'maxlength' => 255]) }}
                        @if ($errors->has('name'))
                            <div class="badge badge-danger">
                                <strong>{{ $errors->first('name') }}</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <button class="btn btn-primary" type="button" data-dismiss="modal" data-original-title="" title="">Закрыть</button>
            <button class="btn btn-success" onclick="AdminDeveloperPage.save(this)" type="button">Сохранить</button>
        </div>
        {{ Form::close() }}
    </div>
</div>