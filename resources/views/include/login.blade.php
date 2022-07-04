<div id="login" class="popup">
    <div class="popup__tabs">
        <div class="popup__tabs--link isActive"><span>{{ $lang['login'] }}</span></div>
        <button type="button" data-src="#register" class="popup__tabs--link" data-fancybox-change-popup><span>{{ $lang['registration'] }}</span>
        </button>
    </div>
    <div class="popup__title">{{ $lang['login'] }}</div>
    {{--<div class="popup__soc">
        <a href="#" class="popup__soc--link"><span class="icon-facebook"></span></a>
        <a href="#" class="popup__soc--link"><span class="icon-google"></span></a>
    </div>
    <div class="popup__or"><span>{{ $lang['or'] }}</span></div>--}}
    <div class="form" id="formLogin">
        {{--load login form--}}
    </div>
</div>
<div id="register" class="popup">
    <div class="popup__tabs">
        <button type="button" data-src="#login" class="popup__tabs--link" data-fancybox-change-popup><span>{{ $lang['login'] }}</span>
        </button>
        <div class="popup__tabs--link isActive"><span>{{ $lang['registration'] }}</span></div>
    </div>
    <div class="popup__title">{{ $lang['registration'] }}</div>
    <div class="form" data-success-text="{{ $lang['verification_link_sent_email'] }}">
        {{--load via ajax--}}
    </div>
</div>
<div id="lostpassword" class="popup">
    <button class="popup__back" data-fancybox-change-popup data-src="#login"><span class="icon-arrow-left"></span>{{ $lang['login'] }}
    </button>
    <div class="popup__title">{{ $lang['password_recovery'] }}</div>
    <div class="popup__subtitle">{{ $lang['type_your_registered_email_account'] }}</div>
    <div class="form" data-success-src="#lostpass-success">
        <div class="form__group">
            {!! Form::email('email_recovery', old('email_recovery'), ['class' => 'form__input', 'required' => 'required', 'autocomplete' => 'off']) !!}
            <label>{{ $lang['your_email'] }}</label>
            <div class="form__required">{{ $lang['enter_valid_email'] }}</div>
        </div>
        {{--<div class="form__group">
            <img src="/images/captcha.png" alt="">
        </div>--}}
        <div class="form__buttons">
            <button type="button" onclick="authPage.restorePassword(this);" data-loading-text="<i class='fa fa-spinner fa-spin '></i> {{ $lang['loading'] }}" class="button">{{ $lang['restore_password'] }}</button>
        </div>
    </div>
</div>
<div id="lostpass-success" class="popup">
    <div class="popup__success">
        <span class="popup__sendmail"></span>
        <div class="popup__subtitle">{{ $lang['password_recovery_instructions'] }}<br>{{ $lang['sent_to_your_mail'] }}</div>
        <div class="popup__text">{{ $lang['did_not_get_email'] }}</div>
        <div class="popup__subtitle">
            {{ $lang['check_your_spam_folder'] }},<br>
            {{ $lang['if_there_no_letter'] }} - <a href="#" data-src="#feedback" data-fancybox-change-popup>{{ $lang['let_us_know'] }}</a>
        </div>
    </div>
</div>