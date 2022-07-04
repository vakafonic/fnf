@extends('manager.app')
@section('title', 'Страницы')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3><i class="icofont icofont-stock-search"></i> Поисковые слова</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item active">Поисковые слова</li>
                    </ol>
                </div>
                <div class="col-lg-6">

                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <!-- Notifications -->
        @include('manager.notifications')
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-content">
                        <div class="row"></div>
                        <div class="m-10">
                            <table role="grid" id="tableSearchWord"
                                   class="table responsive_table dataTables_wrapper"
                                   data-toggle="table"
                                   data-url="{{ route('m.get.search.word') }}"
                                   data-search="true"
                                   data-show-refresh="true"
                                   data-tree-show-field="word"
                                   data-sort-name="count"
                                   data-sort-order="desc"
                                   data-mobile-responsive="true"
                                   data-reorderable-rows="true"
                                   data-use-row-attr-func="true"
                                   data-cookie="true"
                                   data-cookie-id-table="tableSearchWord"
                                   data-side-pagination="server"
                                   data-pagination="true"
                                   data-page-list="[10, 25, 50, 100, ALL]"
                                   data-show-footer="false">
                                <thead>
                                <tr>
                                    <th data-field="word" data-sortable="true">Запрос</th>
                                    <th data-field="count" data-sortable="true" data-align="center" data-width="120">Количество</th>
                                    <th data-field="updated_at" data-sortable="true" data-align="center" data-width="120" data-formatter="publicFormatter">Дата</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('stylesheet')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/bootstrap-table/dist/bootstrap-table.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/bootstrap-table/dist/extensions/tree-column/bootstrap-table-tree-column.css') }}">
@endsection
@section('script')
    <script src="{{ asset('assets/js/bootstrap-table/dist/bootstrap-table.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/locale/bootstrap-table-ru-RU.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/cookie/bootstrap-table-cookie.min.js') }}"></script>
    <script src="{{ asset('assets/js/table_dnd/dist/jquery.tablednd.min.js') }}"></script>
    <script type="application/javascript">
        function publicFormatter(value, rows) {
            return rows.format_ud;
        }
    </script>
@endsection