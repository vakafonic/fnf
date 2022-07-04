@extends('manager.app')
@section('title', 'Страницы')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3><i class="icofont icofont-layout"></i> Страницы</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item active">Страницы</li>
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
                            <table role="grid" id="tablePages"
                                   class="table responsive_table dataTables_wrapper"
                                   data-toggle="table"
                                   data-url="{{ route('m.pages.all') }}"
                                   data-search="true"
                                   data-show-refresh="true"
                                   data-tree-show-field="title"
                                   data-sort-name="created_at"
                                   data-sort-order="desc"
                                   data-mobile-responsive="true"
                                   data-reorderable-rows="true"
                                   data-use-row-attr-func="true"
                                   data-cookie="true"
                                   data-cookie-id-table="tablePages"
                                   data-show-footer="false">
                                <thead>
                                <tr>
                                    <th data-field="name" data-sortable="true">Название</th>
                                    <th data-field="show_top_menu" data-sortable="true" data-align="center" data-width="120" data-formatter="showTopMenuFormatter">В меню</th>
                                    <th data-field="public" data-sortable="true" data-align="center" data-width="120" data-formatter="publicFormatter">Опубликован</th>
                                    <th data-field="id" data-formatter="editFormatter" data-width="120">Действия</th>
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
        function editFormatter(value, row) {
            return '<a href="/pages/edit/' + value + '" class="btn btn-success btn-xs m-r-5" type="button" data-original-title="Редактировать" title="Редактировать"><i class="icofont icofont-edit-alt"></i></a>';
        }
        function publicFormatter(value, row, index) {
            return '<div class="media-body text-center icon-state switch-outline">\n' +
                '<label class="switch">\n' +
                '<input name="check_public" value="' + row.id + '" type="checkbox" ' +
                (value == 1 ? 'checked=""' : '') +
                '><span class="switch-state bg-primary"></span>\n' +
                '</label>\n' +
                '</div>';
        }
        function showTopMenuFormatter(value, row, index) {
            return '<div class="media-body text-center icon-state switch-outline">\n' +
                '<label class="switch">\n' +
                '<input name="check_menu" value="' + row.id + '" type="checkbox" ' +
                (value == 1 ? 'checked=""' : '') +
                '><span class="switch-state bg-primary"></span>\n' +
                '</label>\n' +
                '</div>';
        }
    </script>
@endsection