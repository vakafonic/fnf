@extends('layouts.app')
@section('seo_title', $lang['password_recovery'])
@section('content')

    <div class="container">

        <div class="account">
            <div class="title">
                <div class="title__h1">{{ $lang['password_recovery'] }}</div>
            </div>
            @if($check)
            <form class="form" id="resetPasswordUser" method="POST" {{--data-check-passwords="true" data-success-text="Изменения сохранены"--}}>
                {{ csrf_field() }}
                <div class="form__group">
                    <button type="button" class="form__showpass js-showpass">{{ $lang['show'] }}</button>
                    {!! Form::password('password', ['class' => 'form__input', 'required' => 'required', 'value' => old('email'), 'minlength' => 6]) !!}
                    <label>{{ $lang['create_new_password'] }}</label>
                    <div class="form__required">{{ $lang['fill_field'] }}, {{ $lang['6_characters_minimum'] }}</div>
                </div>
                <div class="form__group">
                    <button type="button" class="form__showpass js-showpass">{{ $lang['show'] }}</button>
                    {!! Form::password('password-confirm', ['class' => 'form__input', 'required' => 'required']) !!}
                    <label>{{ $lang['confirm_password'] }}</label>
                    <div class="form__required">{{ $lang['password_must_match'] }}</div>
                </div>
                <div class="form__buttons">
                    <button type="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> {{ $lang['loading'] }}" class="button">{{ $lang['save_changes'] }}</button>
                </div>
            </form>
            @else
                <div class="jumbotron">
                    <h1 class="display-4">{{ $lang['error'] }}!</h1>
                    <p class="lead">{{ $lang['link_is_invalid'] }}</p>
                    <hr class="my-4">
                    <button data-src="#lostpassword" class="button" data-fancybox-change-popup>{{ $lang['forgot_password'] }}</button>
                </div>
            @endif
        </div>

    </div>
@endsection
@section('script')
    <script type="application/javascript">
        $(function () {
            'use strict';
            //jQuery code here

            $('.js-showpass').on('click', function() {
                if ($(this).is('.isActive')) {
                    $(this).removeClass('isActive').text('{{ $lang['show'] }}');
                    $(this).next('input').attr('type', 'password')
                } else {
                    $(this).addClass('isActive').text('{{ $lang['hide'] }}');
                    $(this).next('input').attr('type', 'text')
                }
            });

            $(document).on('submit', 'form#resetPasswordUser', function (e) {
                if ($(this).find('[name=password]').val() !== $(this).find('[name=password-confirm]').val()) {
                    $(this).find('[name=password-confirm]').val('');
                    return false;
                }

                var thisBtn = $(this).find('button[type="submit"]');
                var textBtn = thisBtn.html();
                $(thisBtn).html($(thisBtn).data('loading-text')).prop('disabled', true);

                $.ajax({
                    type: "POST",
                    url: '{{ route('reset.post.password', ['token' => $token]) }}',
                    cache: false,
                    data: {'password': $(this).find('[name=password]').val()},
                    success: function(data) {
                        //$.fancybox.close();

                        // if (data.success == true) {
                        //     $.fancybox.open({
                        //         src  : '#login',
                        //         opts : {
                        //             buttons : ['close'],
                        //             touch: false,
                        //             baseClass: 'fancybox-popup'
                        //         }
                        //     });
                        // }
                        showAlert(data.message, data.success);
                    },
                    complete: function () {
                        $(thisBtn).html(textBtn).prop('disabled', false);
                    }
                });

                e.preventDefault();
            });
        });
    </script>
@endsection
