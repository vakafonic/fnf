@extends('manager.app')
@section('title', 'Редактировать комментарий')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3>Редактировать комментарий</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item active">Редактировать комментарий</li>
                    </ol>
                </div>
                <div class="col-lg-6">

                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <!-- Notifications -->
        @include('manager.notifications')
        <div class="edit-profile">
            {{ Form::open(['class' => 'card novalidate']) }}
            <div class="row">
                <div class="col-lg-8">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id" value="{{$comment->id}}">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Комментарий {{$comment->name}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label" for="name">Имя</label>
                                    {{ Form::text('name', old('name', $comment->name), ['placeholder' => 'Имя', 'id' => 'name', 'class' => 'form-control']) }}
                                    @if ($errors->has('name'))
                                        <div class="badge badge-danger">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label" for="name">Имя</label>
                                    {{ Form::text('text', old('name', $comment->text), ['placeholder' => 'Текст комментария', 'id' => 'text', 'class' => 'form-control']) }}
                                    @if ($errors->has('text'))
                                        <div class="badge badge-danger">
                                            <strong>{{ $errors->first('text') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('m.comments.all') }}" class="btn btn-dark">Закрыть</a>
                        <button class="btn btn-success" name="action" value="1" type="submit">Сохранить и продолжить</button>
                        <button class="btn btn-primary" name="action" value="2" type="submit">Сохранить и закрыть</button>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection
@push('ls')
    <script>
        $(function() {
            if($('input[type=radio][name=role]:checked').val() == '2') {
                $('#furole').show();
            }

            $('input[type=radio][name=role]').change(function() {
                if (this.value == '2') {
                    $('#furole').show();
                }
                else {
                    $('#furole').hide();
                }
            });
        });
    </script>
@endpush