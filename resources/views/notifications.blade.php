@if ($errors->any())
    <div class="alert js-message isActive alert-danger alert-dismissable margin5">
        <span class="js-message-text">{{ $errors->all(':message<br/>') }}</span>
        <button type="button" class="alert-close js-alert-close"></button>
    </div>
@endif

@if ($message = Session::get('success'))
    <div class="alert js-message isActive isOk alert-success alert-dismissable margin5">
        <span class="js-message-text"> {{ $message }}</span>
        <button type="button" class="alert-close js-alert-close"></button>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert js-message isActive alert-danger alert-dismissable margin5">
        <span class="js-message-text"> {{ $message }}</span>
        <button type="button" class="alert-close js-alert-close"></button>
    </div>
@endif

@if ($message = Session::get('warning'))
    <div class="alert js-message isActive alert-warning alert-dismissable margin5">
        <span class="js-message-text"> {{ $message }}</span>
        <button type="button" class="alert-close js-alert-close"></button>
    </div>
@endif

@if ($message = Session::get('info'))
    <div class="alert js-message isActive alert-info alert-dismissable margin5">
        <span class="js-message-text"> {{ $message }}</span>
        <button type="button" class="alert-close js-alert-close"></button>
    </div>
@endif