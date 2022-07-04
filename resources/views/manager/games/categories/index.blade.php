@extends('manager.app')
@section('title', 'Все пользователи')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3><i class="icofont icofont-ebook"></i> Все категории</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item active">Все категории</li>
                    </ol>
                </div>
                <div class="col-lg-6">
                    <div class="bookmark pull-right">
                        <ul>
                            <li><a href="{{ route('m.games.category.create') }}" data-container="body" data-toggle="popover" data-placement="top" title="" data-original-title="Добавить новую категорию"><i data-feather="plus"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <!-- Notifications -->
        @include('manager.notifications')

    </div>
@endsection
@section('stylesheet')
@endsection
@section('script')
@endsection