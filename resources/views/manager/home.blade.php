@extends('manager.app')
@section('title', 'Главная')
@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-lg-6">
                <h3>{{ config('app.name') }}</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('m.home') }}">Главная</a></li>
                </ol>
            </div>
            <div class="col-lg-6">
                <!-- Bookmark Start-->
                <div class="bookmark pull-right">

                </div>
                <!-- Bookmark Ends-->
            </div>
        </div>
    </div>
</div>
<!-- Container-fluid starts-->
<div class="container-fluid">
    <div class="row sass-top-cards">
        <div class="col">
            <div class="card sass-widgets o-hidden">
                <div class="card-body p-0">
                    <div class="media">
                        <div class="media-body">
                            <p class="f-w-600">Пользователей</p>
                            <h2 class="f-w-600 mb-0">{{ number_format(\App\User::whereStatus(1)->count(), 0, ',', ' ') }}</h2>
                        </div>
                        <div class="setting-dot d-inline-block">
                            <div class="setting-bg setting-bg-secondary"><i class="fa fa-spin fa-cog font-secondary"></i></div>
                        </div>
                    </div>
                    <div class="bg-gradient-secondary footer-shape">
                        <div class="sass-footer">
                            <p class="mb-0 d-inline-block mr-3">{{ number_format(\App\User::whereStatus(1)->where('role', '<', 1)->count(), 0, ',', ' ') }}</p><span><span class="d-inline-block"><i class="fa fa-sort-up mr-4"></i></span></span>
                            <p class="mb-0 d-inline-block b-l-secondary pl-4 mr-3">
                                {{ number_format(\App\User::whereStatus(1)->where('role', '<', 1)->whereDate('created_at', '>', date('Y-m-d', strtotime('-7 days')))->count(), 0, ',', ' ') }}
                            </p><span class="down-arrow-align"><span class="d-inline-block"><i class="fa fa-sort-down"></i></span></span>
                            <div class="small-sass">
                                <div class="small-sasschart-1 flot-chart-container"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card sass-widgets o-hidden">
                <div class="card-body p-0">
                    <div class="media">
                        <div class="media-body">
                            <p class="f-w-600">Игр</p>
                            <h2 class="f-w-600 mb-0">{{ number_format(\App\Models\Game::count(), 0, ',', ' ') }}</h2>
                        </div>
                        <div class="setting-dot d-inline-block">
                            <div class="setting-bg setting-bg-info"><i class="fa fa-spin fa-cog font-info"></i></div>
                        </div>
                    </div>
                    <div class="bg-gradient-info footer-shape">
                        <div class="sass-footer">
                            <p class="mb-0 d-inline-block mr-3">{{ number_format(\App\Models\Game::wherePublic(1)->count(), 0, ',', ' ') }}</p><span><span class="d-inline-block"><i class="fa fa-sort-up mr-4"></i></span></span>
                            <p class="mb-0 d-inline-block b-l-info pl-4 mr-3">
                                {{ number_format(\App\Models\Game::wherePublic(1)->whereDate('created_at', '>', date('Y-m-d', strtotime('-7 days')))->count(), 0, ',', ' ') }}
                            </p><span class="down-arrow-align"><span class="d-inline-block"><i class="fa fa-sort-down"></i></span></span>
                            <div class="small-sass">
                                <div class="small-sasschart-2 flot-chart-container"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card sass-widgets o-hidden">
                <div class="card-body p-0">
                    <div class="media">
                        <div class="media-body">
                            <p class="f-w-600">Жанров</p>
                            <h2 class="f-w-600 mb-0">{{ number_format(\App\Models\Genre::count(), 0, ',', ' ') }}</h2>
                        </div>
                        <div class="setting-dot d-inline-block">
                            <div class="setting-bg setting-bg-warning"><i class="fa fa-spin fa-cog font-warning"></i></div>
                        </div>
                    </div>
                    <div class="bg-gradient-warning footer-shape">
                        <div class="sass-footer">
                            <p class="mb-0 d-inline-block mr-3">{{ number_format(\App\Models\Genre::wherePublic(1)->count(), 0, ',', ' ') }}</p><span><span class="d-inline-block"><i class="fa fa-sort-up mr-4"></i></span></span>
                            <p class="mb-0 d-inline-block b-l-warning pl-4 mr-3">
                                {{ number_format(\App\Models\Genre::wherePublic(1)->whereDate('created_at', '>', date('Y-m-d', strtotime('-7 days')))->count(), 0, ',', ' ') }}
                            </p><span class="down-arrow-align"><span class="d-inline-block"><i class="fa fa-sort-down"></i></span></span>
                            <div class="small-sass">
                                <div class="small-sasschart-3 flot-chart-container"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card sass-widgets o-hidden">
                <div class="card-body p-0">
                    <div class="media">
                        <div class="media-body">
                            <p class="f-w-600">Героев</p>
                            <h2 class="f-w-600 mb-0">{{ number_format(\App\Models\Heroes::count(), 0, ',', ' ') }}</h2>
                        </div>
                        <div class="setting-dot d-inline-block">
                            <div class="setting-bg setting-bg-success"><i class="fa fa-spin fa-cog font-success"></i></div>
                        </div>
                    </div>
                    <div class="bg-gradient-success footer-shape">
                        <div class="sass-footer">
                            <p class="mb-0 d-inline-block mr-3">{{ number_format(\App\Models\Heroes::wherePublic(1)->count(), 0, ',', ' ') }}</p><span><span class="d-inline-block"><i class="fa fa-sort-up mr-4"></i></span></span>
                            <p class="mb-0 d-inline-block b-l-success pl-4 mr-3">
                                {{ number_format(\App\Models\Heroes::wherePublic(1)->whereDate('created_at', '>', date('Y-m-d', strtotime('-7 days')))->count(), 0, ',', ' ') }}
                            </p><span class="down-arrow-align"><span class="d-inline-block"><i class="fa fa-sort-down"></i></span></span>
                            <div class="small-sass">
                                <div class="small-sasschart-4 flot-chart-container"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Container-fluid Ends-->
@endsection
@section('stylesheet')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/chartist.css') }}">
@endsection
@section('script')
    <script src="{{ asset('assets/js/chart/chartist/chartist.js') }}"></script>
    <script src="{{ asset('assets/js/chart/chartist/chartist-plugin-tooltip.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js') }}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/stock-prices.js') }}"></script>
    <script type="application/javascript">
        new Chartist.Bar('.small-sasschart-1', {
            labels: ['Q1', 'Q2', 'Q3', 'Q4', 'Q5'],
            series: [
                [
                    {{ \App\User::whereStatus(1)->where('role', '<', 1)->whereDate('created_at', '>=', date('Y-m-d', strtotime('-35 days')))->whereDate('created_at', '<', date('Y-m-d', strtotime('-28 days')))->count() }},
                    {{ \App\User::whereStatus(1)->where('role', '<', 1)->whereDate('created_at', '>=', date('Y-m-d', strtotime('-28 days')))->whereDate('created_at', '<', date('Y-m-d', strtotime('-21 days')))->count() }},
                    {{ \App\User::whereStatus(1)->where('role', '<', 1)->whereDate('created_at', '>=', date('Y-m-d', strtotime('-21 days')))->whereDate('created_at', '<', date('Y-m-d', strtotime('-14 days')))->count() }},
                    {{ \App\User::whereStatus(1)->where('role', '<', 1)->whereDate('created_at', '>=', date('Y-m-d', strtotime('-14 days')))->whereDate('created_at', '<', date('Y-m-d', strtotime('-7 days')))->count() }},
                    {{ \App\User::whereStatus(1)->where('role', '<', 1)->whereDate('created_at', '>=', date('Y-m-d', strtotime('-7 days')))->count() }}
                ]
            ]
        }, {
            plugins: [
                Chartist.plugins.tooltip()
            ],
            axisY: {
                low: 0,
                showGrid: false,
                showLabel: false,
                offset: 0
            },
            axisX: {
                showGrid: false,
                showLabel: false,
                offset: 0
            }
        }).on('draw', function(data) {
            if(data.type === 'bar') {
                data.element.attr({
                    style: 'stroke-width: 2px'
                });
            }
        });

        new Chartist.Bar('.small-sasschart-2', {
            labels: ['Q1', 'Q2', 'Q3', 'Q4', 'Q5'],
            series: [
                [
                    {{ \App\Models\Game::wherePublic(1)->whereDate('created_at', '>=', date('Y-m-d', strtotime('-35 days')))->whereDate('created_at', '<', date('Y-m-d', strtotime('-28 days')))->count() }},
                    {{ \App\Models\Game::wherePublic(1)->whereDate('created_at', '>=', date('Y-m-d', strtotime('-28 days')))->whereDate('created_at', '<', date('Y-m-d', strtotime('-21 days')))->count() }},
                    {{ \App\Models\Game::wherePublic(1)->whereDate('created_at', '>=', date('Y-m-d', strtotime('-21 days')))->whereDate('created_at', '<', date('Y-m-d', strtotime('-14 days')))->count() }},
                    {{ \App\Models\Game::wherePublic(1)->whereDate('created_at', '>=', date('Y-m-d', strtotime('-14 days')))->whereDate('created_at', '<', date('Y-m-d', strtotime('-7 days')))->count() }},
                    {{ \App\Models\Game::wherePublic(1)->whereDate('created_at', '>=', date('Y-m-d', strtotime('-7 days')))->count() }}
                ]
            ]
        }, {
            plugins: [
                Chartist.plugins.tooltip()
            ],
            axisY: {
                low: 0,
                showGrid: false,
                showLabel: false,
                offset: 0
            },
            axisX: {
                showGrid: false,
                showLabel: false,
                offset: 0
            }
        }).on('draw', function(data) {
            if(data.type === 'bar') {
                data.element.attr({
                    style: 'stroke-width: 2px'
                });
            }
        });

        new Chartist.Bar('.small-sasschart-3', {
            labels: ['Q1', 'Q2', 'Q3', 'Q4', 'Q5'],
            series: [
                [
                    {{ \App\Models\Genre::wherePublic(1)->whereDate('created_at', '>=', date('Y-m-d', strtotime('-35 days')))->whereDate('created_at', '<', date('Y-m-d', strtotime('-28 days')))->count() }},
                    {{ \App\Models\Genre::wherePublic(1)->whereDate('created_at', '>=', date('Y-m-d', strtotime('-28 days')))->whereDate('created_at', '<', date('Y-m-d', strtotime('-21 days')))->count() }},
                    {{ \App\Models\Genre::wherePublic(1)->whereDate('created_at', '>=', date('Y-m-d', strtotime('-21 days')))->whereDate('created_at', '<', date('Y-m-d', strtotime('-14 days')))->count() }},
                    {{ \App\Models\Genre::wherePublic(1)->whereDate('created_at', '>=', date('Y-m-d', strtotime('-14 days')))->whereDate('created_at', '<', date('Y-m-d', strtotime('-7 days')))->count() }},
                    {{ \App\Models\Genre::wherePublic(1)->whereDate('created_at', '>=', date('Y-m-d', strtotime('-7 days')))->count() }}
                ]
            ]
        }, {
            plugins: [
                Chartist.plugins.tooltip()
            ],
            axisY: {
                low: 0,
                showGrid: false,
                showLabel: false,
                offset: 0
            },
            axisX: {
                showGrid: false,
                showLabel: false,
                offset: 0
            }
        }).on('draw', function(data) {
            if(data.type === 'bar') {
                data.element.attr({
                    style: 'stroke-width: 2px'
                });
            }
        });

        new Chartist.Bar('.small-sasschart-4', {
            labels: ['Q1', 'Q2', 'Q3', 'Q4', 'Q5'],
            series: [
                [
                    {{ \App\Models\Heroes::wherePublic(1)->whereDate('created_at', '>=', date('Y-m-d', strtotime('-35 days')))->whereDate('created_at', '<', date('Y-m-d', strtotime('-28 days')))->count() }},
                    {{ \App\Models\Heroes::wherePublic(1)->whereDate('created_at', '>=', date('Y-m-d', strtotime('-28 days')))->whereDate('created_at', '<', date('Y-m-d', strtotime('-21 days')))->count() }},
                    {{ \App\Models\Heroes::wherePublic(1)->whereDate('created_at', '>=', date('Y-m-d', strtotime('-21 days')))->whereDate('created_at', '<', date('Y-m-d', strtotime('-14 days')))->count() }},
                    {{ \App\Models\Heroes::wherePublic(1)->whereDate('created_at', '>=', date('Y-m-d', strtotime('-14 days')))->whereDate('created_at', '<', date('Y-m-d', strtotime('-7 days')))->count() }},
                    {{ \App\Models\Heroes::wherePublic(1)->whereDate('created_at', '>=', date('Y-m-d', strtotime('-7 days')))->count() }}
                ]
            ]
        }, {
            plugins: [
                Chartist.plugins.tooltip()
            ],
            axisY: {
                low: 0,
                showGrid: false,
                showLabel: false,
                offset: 0
            },
            axisX: {
                showGrid: false,
                showLabel: false,
                offset: 0
            }
        }).on('draw', function(data) {
            if(data.type === 'bar') {
                data.element.attr({
                    style: 'stroke-width: 2px'
                });
            }
        });
    </script>
@endsection