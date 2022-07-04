<div class="form__group">
    {!! Form::email('email', old('email'), ['class' => 'form__input', 'required' => 'required', 'autocomplete' => 'off']) !!}
    <label>{{ $lang['your_email'] }}</label>
    <div class="message">{{ $lang['enter_valid_email'] }}</div>
</div>
<div class="form__group">
    <button type="button" onclick="mainSite.jsShowpassBtn(this);" class="form__showpass js-showpass">{{ $lang['show'] }}</button>
    {!! Form::password('password', ['class' => 'form__input', 'required' => 'required', 'autocomplete' => 'off']) !!}
    <label>{{ $lang['enter_password'] }}</label>
    <div class="form__required">{{ $lang['fill_field'] }}</div>
</div>
<div class="form__lost">
    <a href="#" data-src="#lostpassword" data-fancybox-change-popup>{{ $lang['forgot_password'] }}</a>
    <div class="css3check">
        <label>
            {!! Form::checkbox('remember', 1, old('remember')) !!}
            <span>{{ $lang['remember_me'] }}</span>
        </label>
    </div>
</div>
<div class="form__buttons">
    {{--<input type="button" onclick="authPage.loginForm(this);" value="{{ $lang['login_1'] }}" class="button">--}}
    <button type="button" onclick="authPage.loginForm(this);" data-loading-text="<i class='fa fa-spinner fa-spin '></i> {{ $lang['loading'] }}" class="button">
        {{ $lang['login_1'] }}
    </button>
</div>