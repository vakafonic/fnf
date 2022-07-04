<div class="popup__title">{{ $lang['feedback'] }}</div>
<div class="form" data-success-text="{{ $lang['thank_you_your_message'] }}">
    <div class="form__group">
        <input type="email" name="email" class="form__input" required>
        <label>{{ $lang['your_email'] }}</label>
        <div class="form__required">{{ $lang['enter_valid_email'] }}</div>
    </div>
    <div class="form__group">
        <textarea rows="6" name="message" class="form__input" required></textarea>
        <label>{{ $lang['your_message'] }}</label>
        <div class="form__required">{{ $lang['fill_field'] }}</div>
    </div>
    <div class="form__buttons">
        <button type="button" onclick="feedbackSite.sendForm(this);" class="button" data-loading-text="<i class='fa fa-spinner fa-spin '></i> {{ $lang['loading'] }}">{{ $lang['submit'] }}</button>
    </div>
</div>