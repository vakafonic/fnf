<div class="form__group">
    {!! Form::email('email', old('email', ($email ?? '')), ['class' => 'form__input' . ($errors->has('email') ? ' invalid required' : ''), 'required' => 'required']) !!}
    <label>{{ $lang['your_email'] }}</label>
    @if ($errors->has('email'))
        <div class="form__required">{{ $errors->first('email') }}</div>
    @else
        <div class="form__required">{{ $lang['enter_valid_email'] }}</div>
    @endif
</div>
<div class="form__group">
    <button type="button" onclick="mainSite.jsShowpassBtn(this);" class="form__showpass js-showpass">{{ $lang['show'] }}</button>
    {!! Form::password('password', ['class' => 'form__input' . ($errors->has('password') ? ' invalid required' : ''), 'required' => 'required', 'value' => old('email', ($password ?? ''))]) !!}
    <label>{{ $lang['enter_password'] }}</label>
    @if ($errors->has('password'))
        <div class="form__required">{{ $errors->first('password') }}</div>
    @else
        <div class="form__required">{{ $lang['fill_field'] }}</div>
    @endif
</div>
<div class="form__group">
    {!! Form::text('username', old('username', ($username ?? '')), ['class' => 'form__input' . ($errors->has('username') ? ' invalid required' : ''), 'required' => 'required']) !!}
    <label>{{ $lang['nickname'] }}</label>
    @if ($errors->has('username'))
        <div class="form__required">{{ $errors->first('username') }}</div>
    @else
        <div class="form__required">{{ $lang['fill_field'] }}</div>
    @endif
</div>
{{--<div class="form__group">
    <img src="/images/captcha.png" alt="">
</div>--}}
<div class="form__buttons">
    <button onclick="authPage.sendRegister(this);" type="button" data-loading-text="<i class='fa fa-spinner fa-spin '></i> {{ $lang['registration'] }}" class="button">{{ $lang['sign_up'] }}</button>
</div>