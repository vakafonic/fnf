@extends('manager.app')
@section('title', 'Новый пользователь')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3>Новый пользователь</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item active">Новый пользователь</li>
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
            {{ Form::open(['route' => 'm.users.create', 'class' => 'card novalidate']) }}
                <div class="row">
                    <div class="col-lg-8">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" value="{{$user->id}}">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Новый пользователь</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label" for="name">Имя</label>
                                        {{ Form::text('name', old('name', $user->name), ['placeholder' => 'Петя Петров', 'id' => 'name', 'class' => 'form-control']) }}
                                        @if ($errors->has('name'))
                                            <div class="badge badge-danger">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="username">Ник</label>
                                        {{ Form::text('username', old('username', $user->username), ['placeholder' => 'Pups', 'id' => 'username', 'class' => 'form-control']) }}
                                        @if ($errors->has('username'))
                                            <div class="badge badge-danger">
                                                <strong>{{ $errors->first('username') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="username">Email</label>
                                        {{ Form::email('email', old('email', $user->email), ['placeholder' => 'your-email@domain.com', 'id' => 'email', 'class' => 'form-control']) }}
                                        @if ($errors->has('email'))
                                            <div class="badge badge-danger">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="password">Пароль</label>
                                        {{ Form::password('password', ['class' => 'form-control', 'id' => 'password']) }}
                                        @if ($errors->has('password'))
                                            <div class="badge badge-danger">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="confirmed">Повторить пароль</label>
                                        {{ Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'confirmed']) }}
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="checkbox checkbox-primary">
                                        {{ Form::checkbox('status', 1, old('status', 1) == 1, ['id' => 'status']) }}
                                        <label for="status">Активный</label>
                                    </div>
                                </div>
                            </div>
                            <div id="furole" class="row" style="display: none">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title mb-0">Роли</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                @if ($errors->has('role_select'))
                                                    <div class="col-md-12">
                                                        <div class="badge badge-danger">
                                                            <strong>{{ $errors->first('role_select') }}</strong>
                                                        </div>
                                                    </div>
                                                @endif
                                                @foreach($role_prefix as $rp)
                                                    <div class="col-md-6">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <h5>{{ $rp->name }}</h5>
                                                            </div>
                                                            <div class="col">
                                                                <div class="form-group m-t-15">
                                                                    @foreach($rp->roles as $role)
                                                                    <div class="checkbox checkbox-primary">
                                                                        {{ Form::checkbox($rp->prefix.'_'.$role->alias, 1, old($rp->prefix.'_'.$role->alias) == 1, ['id' => $rp->prefix.'_'.$role->alias]) }}
                                                                        <label for="{{ $rp->prefix }}_{{ $role->alias }}">{{ $role->name }}</label>
                                                                    </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="{{ route('m.users') }}" class="btn btn-dark">Закрыть</a>
                            <button class="btn btn-primary" type="submit">Создать нового пользователя</button>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Права пользователя</h4>
                            </div>
                            <div class="card-body megaoptions-border-space-sm">
                                <div class="mega-inline">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="media p-20">
                                                    <div class="radio radio-primary mr-3">
                                                        {{ Form::radio('role', 0, old('role', $user->role) < 1, ['id' => 'radio18']) }}
                                                        <label for="radio18"></label>
                                                    </div>
                                                    <div class="media-body">
                                                        <h6 class="mt-0 mega-title-badge">Пользователь
                                                        </h6>
                                                        <p>Веб пользователь. Не имеет никакого доступа в админ панель</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="media p-20">
                                                    <div class="radio radio-success mr-3">
                                                        {{ Form::radio('role', 2, old('role', $user->role) == 2, ['id' => 'radio19']) }}
                                                        <label for="radio19"></label>
                                                    </div>
                                                    <div class="media-body">
                                                        <h6 class="mt-0 mega-title-badge">Менеджер
                                                        </h6>
                                                        <p>Пользователь имет доступ в админ панель с ограничеными правами (создание, удаление, редактирование...)</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="media p-20">
                                                    <div class="radio radio-secondary mr-3">
                                                        {{ Form::radio('role', 1, old('role', $user->role) == 1, ['id' => 'radio20']) }}
                                                        <label for="radio20"></label>
                                                    </div>
                                                    <div class="media-body">
                                                        <h6 class="mt-0 mega-title-badge">Администратор
                                                        </h6>
                                                        <p>Администратор сайта. Имеет все права в админ панеле</p>
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