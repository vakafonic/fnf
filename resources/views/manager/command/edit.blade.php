<div class="modal-body">
    <div class="row">
        <div class="col-md-12">@include('manager.notifications')</div>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id" value="{{ $buttonsPlay->id }}">
        @foreach (\App\Models\Language::all() as $lang)
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label" for="name"> {{ $lang->name }}
                        {{--@if ($lang->main == 1)
                            <span class="text-danger">*</span>
                        @endif--}}
                    </label>
                    {{ Form::text($lang->code, old($lang->code, $buttonsPlay->getName($lang->id)), ['placeholder' => '', 'id' => $lang->code, 'class' => 'form-control', 'maxlength' => 255]) }}
                    @if ($errors->has($lang->code))
                        <div class="badge badge-danger">
                            <strong>{{ $errors->first($lang->code) }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
<div class="card-footer text-right">
    <button class="btn btn-primary" type="button" data-dismiss="modal" data-original-title="" title="">Закрыть</button>
    <button class="btn btn-success" onclick="AdminPageComand.save({{ $buttonsPlay->id }})" type="button">Сохранить</button>
</div>