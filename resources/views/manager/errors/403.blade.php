@extends('manager.error')
@section('content')
<!-- page-wrapper Start-->
<div class="page-wrapper">
    <!-- error-403 start-->
    <div class="error-wrapper">
        <div class="container"><img class="img-100" src="/images/other-images/sad.png" alt="">
            <div class="error-heading">
                <h2 class="headline font-success">403</h2>
            </div>
            <div class="col-md-8 offset-md-2">
                <p class="sub-content">{{ $message or 'Ошибка' }}</p>
            </div>
            <div><a class="btn btn-success-gradien btn-lg" href="/"> Вернуться на главную</a></div>
        </div>
    </div>
    <!-- error-403 end-->
</div>
@stop
