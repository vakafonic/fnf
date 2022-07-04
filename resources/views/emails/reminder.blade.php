@extends('layouts.email')
@section('content')
<h6 style="font-weight: 600">{{ $lang['hello'] }} {{ $user->name }}</h6>
<p>{{ $lang['you_receiving_email_password_reset'] }}</p>
<p style="text-align: center"><a href="{{ $link ?? '' }}" style="padding: 10px; background-color: #158df7; color: #fff; display: inline-block; border-radius: 4px">{{ $lang['reset_assword'] }}</a></p>
<p>{{ $lang['if_you_remember_password_ignore_email'] }}</p>
<p>{{ $lang['good_luck_hope_works'] }}.</p>
@endsection