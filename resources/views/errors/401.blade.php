@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="search">
            <div class="search__404">
                {{ $lang['error'] }} <b>401</b>
            </div>
            <div class="search__title">
                {{ $lang['access_denied'] }}
            </div>

            <div class="search__404--text">{{ $lang['link_you_click_correct_outdated'] }}</div>
        </div>
    </div>
@endsection