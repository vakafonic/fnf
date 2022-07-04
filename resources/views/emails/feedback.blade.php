@extends('layouts.email')
@section('content')
    <h6 style="font-weight: 600">{{ $lang['hello'] }} {{ $username }}</h6>
    <p>С сайта {{ config('app.name', 'Gamesgo') }} отправленно сообщение</p>
    <p> <strong>Email пользователя: </strong>{{ $email }}<br/>
        <strong>Ссылка: </strong>{{ $link }}<br/>
        <strong>Сообщение: </strong>{{ $messagef }}
    </p>
    <p></p>
    <p>{{ $lang['good_luck_hope_works'] }}.</p>
@endsection