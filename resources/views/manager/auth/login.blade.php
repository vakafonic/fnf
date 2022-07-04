@extends('manager.app')
@section('title', 'Вход в админ панель')
@section('page-wrapper')
    <div class="container-fluid p-0">
        <!-- login page start-->
        <div class="authentication-main m-0">
            <div class="row">
                <div class="col-md-12">
                    <div class="auth-innerright">
                        <div class="authentication-box">
                            <div class="card-body h-100-d-center">
                                <div class="cont text-center b-light">
                                    <div>
                                        <form class="theme-form" method="POST" action="{{ route('m.login.post') }}">
                                            {{ csrf_field() }}
                                            <h4>ВХОД</h4>
                                            <h6>Введите свой емейл и пароль</h6>
                                            @if($errors->has('message1'))
                                                <div class="alert alert-danger outline" role="alert">
                                                    <p>{{$errors->first('message1')}}</p>
                                                </div>
                                            @endif
                                            <div class="form-group">
                                                <label for="email" class="col-md-4 control-label">E-Mail адресс</label>
                                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>
                                                @if ($errors->has('email'))
                                                    <div class="invalid-feedback">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="password" class="col-md-4 control-label">Пароль</label>
                                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                                @if ($errors->has('password'))
                                                    <div class="invalid-feedback">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="checkbox p-0">
                                                <input id="checkbox1" name="remember" value="1" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                                                <label for="checkbox1">Запомнить меня</label>
                                            </div>
                                            <div class="form-group form-row mt-3 mb-0">
                                                <button class="btn btn-primary btn-block" type="submit">ВХОД</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="sub-cont">
                                        <div class="img">
                                            <div class="img__text m--up">
                                                <h2>Админ панель</h2>
                                                <p>Админ панель для управления сайтом {{ config('app.name', 'Gamesgo') }}!</p>
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
        <!-- login page end-->
    </div>
@endsection
@section('script')
<script src="{{ asset('assets/js/login.js') }}"></script>
@endsection