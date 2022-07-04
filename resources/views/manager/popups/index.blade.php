@extends('manager.app')
@section('title', 'Редактор Pop Up окон')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3><i class="icofont icofont-racing-flag"></i> Редактор Pop Up окон</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item active">Редактор Pop Up окон</li>
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
                    <div class="card-header">
                        <h5 class="m-b-0">Редактор Pop Up окон</h5>
                    </div>
                    <div class="card-content">
                        <div class="row"></div>
                        <div class="m-10">
                            <table role="grid" id="tableTranslationGet"
                                   class="table responsive_table dataTables_wrapper"
                                   {{--data-method="post"--}}
                                   data-toggle="table"
                                   data-url="{{ route('m.popups.all') }}"
                                   data-id-field="id"
                                   data-search="true"
                                   data-show-refresh="true"
                                   {{--data-parent-idfield="parent_id"
                                   data-root-parent-id="0"--}}
                                   data-tree-show-field="title"
                                   data-sort-name="id"
                                   data-sort-order="desc"
                                   data-mobile-responsive="true"
                                   {{--data-reorderable-rows="true"--}}
                                   data-use-row-attr-func="true"
                                   data-cookie="true"
                                   data-cookie-id-table="tablePopupsGet"
                                   data-minimum-count-columns="2"
                                   data-pagination="true"
                                   data-page-list="[10, 25, 50, 100, ALL]"
                                   data-show-footer="false">
                                <thead>
                                <tr>
                                    <th data-field="name" data-sortable="false">Название</th>
                                    <th data-field="id" data-width="120" data-formatter="editFormatter">Действия</th>
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
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/reorder-rows/bootstrap-table-reorder-rows.min.js') }}"></script>
    <script src="{{ asset('assets/js/table_dnd/dist/jquery.tablednd.min.js') }}"></script>
    <script type="application/javascript">
        function editFormatter(value, row) {
            return '<a href="/popups/edit/' + value + '" class="btn btn-success btn-xs m-r-5" ' +
                'data-original-title="Редактировать" title="Редактировать"><i class="icofont icofont-edit-alt"></i></a>';
        }
    </script>
@endsection