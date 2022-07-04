@extends('layouts.app')
@section('seo_title', $lang['user_account'])
@section('content')
    <section>
        <div class="container">
            <div class="profile">
                <div class="profile-col-first">
                    <div class="user-bl">
                        <div class="user-head">
                            <div class="user-img-wrap js-avatar-opener" href="#">
                                @if(Auth::user()->isAvatar())
                                    <img class="userpic-img"
                                         src="{{ Auth::user()->big_avatar }}?time={{ strtotime(Auth::user()->update_at) }}"
                                         alt="placeholder">
                                @else
                                    <span class="user-letter">{{ mb_substr(Auth::user()->username, 0, 1) }}</span>
                                @endif
                                <div class="user-img-changer">{{ $lang['change'] }}</div>
                            </div>
                            <div class="user-info">
                                <span class="user-nick">{{ Auth::user()->username }}</span>
                                <span class="user-mail">{{Auth::user()->email}}</span>
                            </div>
                        </div>
                        <ul class="user-list">
                            <li class="user-item">
                                <a class="user-link js-pass-changer" href="#">{{ $lang['change_password'] }}</a>
                            </li>
                            <li class="user-item">
                                <a class="user-link" href="{{ route('logout') }}">{{ $lang['logout'] }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="profile-col-last">
                    {{ Form::open(['id' => 'changeAccount', 'method' => 'POST']) }}
                    {!! Form::hidden('_token', csrf_token()) !!}
                    <div class="form-head">
                        <h1>{{ $lang['profile'] }}</h1>
                        <div class="profile-mob-user">
                            <div class="user-img-wrap">
                                @if(Auth::user()->isAvatar())
                                    <img class="user-img"
                                         src="{{ Auth::user()->big_avatar }}?time={{ strtotime(Auth::user()->update_at) }}"
                                         alt="placeholder">
                                @else
                                    <span class="user-letter">{{ mb_substr(Auth::user()->username, 0, 1) }}</span>
                                @endif
                            </div>
                            <button class="profile-user-changer" type="button">
                                {{$lang['change_avatar']}}</button>
                        </div>
                    </div>
                    <div class="form-body">
                        <div class="form-control">
                            <div class="form-control-inner" id="nameProfile">
                                {!! Form::text('username', old('username', Auth::user()->username), ['class' => 'form-field']) !!}
                                <label class="form-label">{{ $lang['your_nickname'] }}</label>
                            </div>
                            <div class="required-bl" style="display: none">
                                <span class="required-bl-text ">{{ $lang['fill_field'] }}</span>
                            </div>
                        </div>
                        <div class="form-control">
                            <div class="form-control-inner" id="emailProfile">
                                {!! Form::email('email', old('email', Auth::user()->email), ['class' => 'form-field has-btn']) !!}
                                <label class="form-label">E-mail</label>
                            </div>
                            <div class="required-bl" style="display: none;">
                                <span class="required-bl-text"
                                      style="display: inline">{{ $lang['invalid_email'] }}</span>
                            </div>
                        </div>
                        <button class="primary-btn" type="submit">
                            <span class="primary-btn-text">{{ $lang['save_changes'] }}</span>
                        </button>
                    </div>
                    {{ Form::close() }}
                    <div class="profile-btns">
                        <button class="profile-btn js-pass-changer"
                                type="button">{{ $lang['change_password'] }}</button>
                        <a class="profile-btn" type="button" href="{{route('logout')}}">{{ $lang['logout'] }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        $(function () {
            'use strict';
            //jQuery code here

            $(document).on('submit', '#changeAccount', function (e) {
                e.preventDefault();

                let form = $(this);
                let url = form.attr('action');
                let $errorClass = 'has-invalid-field';

                form.find('button[type="submit"]').html($loadingBtnTxt).prop('disabled', true);

                let thisUsername = form.find('[name=username]').val(),
                    thisEmail = form.find('[name=email]').val();

                let emailProfile = $('#emailProfile');
                let nameProfile = $('#nameProfile');

                let flagRet = false;

                if (thisEmail.length < 1) {
                    emailProfile.addClass($errorClass);
                    emailProfile.closest('.form-control').find('.required-bl').show();
                    flagRet = true;
                }
                if (thisUsername.length < 1) {
                    nameProfile.addClass($errorClass);
                    nameProfile.closest('.form-control').find('.required-bl').show();
                }

                if (flagRet) {
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: '{{ route('account.update') }}',
                    data: form.serialize(), // serializes the form's elements.
                    success: function (data) {
                        if (data.error) {
                            showAlert(data.message, false);
                        } else {
                            showAlert(data.message, true);
                            $('.updateUsername').text(data.input_all.username);
                            $('.updateUserEmail').text(data.input_all.email);
                            $('.updateUsernameOne').text((data.input_all.username).substr(0, 1));
                        }
                    },
                    complete: function () {
                        form.find('button[type="submit"]').html("{{ $lang['save_changes'] }}").prop('disabled', false);
                    }
                });

            });

            $(document).on('submit', 'form#changePassword', function (e) {
                $('.password_must').hide();
                e.preventDefault();

                // if ($(this).find('[name=newpassword]').val() !== $(this).find('[name=newpassword_confirmation]').val()) {
                //     $(this).find('[name=newpassword_confirmation]').val('');
                //     $('.password_must').show();
                //     return false;
                // }

                var form = $(this);
                var url = form.attr('action');

                form.find('button[type="submit"]').html($loadingBtnTxt).prop('disabled', true);

                $.ajax({
                    type: "POST",
                    url: '{{ route('account.change.password') }}',
                    data: form.serialize(), // serializes the form's elements.
                    success: function (data) {
                        if (data.error) {
                            showAlert(data.message, false);
                        } else {
                            // $.fancybox.close();
                            var $target = $('.wrapper');
                            var trigger = 'has-open-pass-change';

                            form.trigger("reset");
                            showAlert(data.message, true);

                            if ($target.hasClass(trigger)) {
                                setTimeout(function() {
                                    $target.removeClass(trigger);
                                }, 7000)
                            }
                        }
                    },
                    complete: function () {
                        form.find('button[type="submit"]').html("{{ $lang['save_changes'] }}").prop('disabled', false);
                        /*jsShowpass();*/
                    }
                });

            });

            jsShowpass();
        });
    </script>
@endsection