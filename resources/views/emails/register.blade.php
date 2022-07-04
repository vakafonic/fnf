@extends('layouts.email')
@section('content')
    <h6 style="font-weight: 600">{{ $lang['hello'] }} {{ $user->name }}</h6>
    <p>{{ $lang['you_have_registered_site'] }} {{ config('app.name', 'Gamesgo') }}. {{ $lang['please_activate_your_account'] }}</p>
    <p style="text-align: center"><a href="{{ $link ?? '' }}" style="padding: 10px; background-color: #158df7; color: #fff; display: inline-block; border-radius: 4px">{{ $lang['аctivate_account'] }}</a></p>
    <p>{{ $lang['if_you_have_not_registered'] }}</p>
    <p>{{ $lang['good_luck_hope_works'] }}.</p>
@endsection