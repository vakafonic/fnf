<div id="changepass" class="popup">
    <div class="popup__title">{{ $lang['change_assword'] }}</div>
    {{ Form::open(['class' => 'form', 'id' => 'changePassword', 'method' => 'POST']) }}
    {!! Form::hidden('_token', csrf_token()) !!}
    {{--<form class="form" data-check-passwords="true" data-success-text="{{ $lang['changes_saved'] }}">--}}
        <div class="form__group">
            <button type="button" class="form__showpass js-showpass">{{ $lang['show'] }}</button>
            {!! Form::password('oldpassword', ['class' => 'form__input', 'required' => 'required']) !!}
            <label>{{ $lang['enter_current_password'] }}</label>
            <div class="form__required">{{ $lang['fill_field'] }}</div>
        </div>
        <div class="form__group">
            <button type="button" class="form__showpass js-showpass">{{ $lang['show'] }}</button>
            {!! Form::password('newpassword', ['class' => 'form__input', 'required' => 'required']) !!}
            <label>{{ $lang['create_new_password'] }}</label>
            <div class="form__required">{{ $lang['fill_field'] }}</div>
        </div>
        <div class="form__group">
            <button type="button" class="form__showpass js-showpass">{{ $lang['show'] }}</button>
            {!! Form::password('newpassword_confirmation', ['class' => 'form__input', 'required' => 'required']) !!}
            <label>{{ $lang['confirm_password'] }}</label>
            <div class="form__required">{{ $lang['password_must_match'] }}</div>
        </div>
        <div class="form__buttons">
            <button type="submit" class="button">{{ $lang['save'] }}</button>
        </div>
    {{ Form::close() }}
</div>
<div id="avatar" class="popup">
    <div class="popup__title">{{ $lang['change_avatar'] }}</div>
    {{ Form::open(['class' => 'form', 'id' => 'changeAvatar', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
    {!! Form::hidden('_token', csrf_token()) !!}
    {{--<form class="form" data-success-text="{{ $lang['saved'] }}">--}}
        <div class="upload">
            <div class="upload__tip">{{ $lang['image_minimum_size_180'] }}</div>
            <label class="upload__button">
                <span class="upload__button--select">{{ $lang['select_file'] }}</span>
                <span class="upload__button--file"></span>
                <input type="file" name="avatar" accept=".png,.jpg,.jpeg" class="upload__input js-avatar-upload">
            </label>
        </div>
        <div class="css3switch">
            <label>
                {!! Form::checkbox('delete', '1', null,  ['id' => 'deleteAvatar']) !!}
                <span>{{ $lang['delete_photo'] }}</span>
            </label>
        </div>
        <div class="form__buttons">
            <button type="submit" class="button">{{ $lang['change_avatar'] }}</button>
        </div>
    {{ Form::close() }}
</div>