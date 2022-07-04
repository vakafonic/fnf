@extends('manager.app')
@section('title', 'Редактор языка')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3><i class="icofont icofont-racing-flag"></i> Редактор языка</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item active">Редактор языка</li>
                    </ol>
                </div>
                <div class="col-lg-6">
                    <div class="bookmark pull-right">
                        <ul>
                            <li><a href="javascript:void(0)" onclick="AdminTransPage.newTrans()" data-container="body" data-toggle="popover" data-placement="top" title="" data-original-title="Добавить "><i data-feather="plus"></i></a></li>
                        </ul>
                    </div>
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
                        <h5 class="m-b-0">Редактор языка</h5>
                    </div>
                    <div class="card-content">
                        <div class="row"></div>
                        <div class="m-10">
                            <table role="grid" id="tableTranslationGet"
                                   class="table responsive_table dataTables_wrapper"
                                   {{--data-method="post"--}}
                                   data-toggle="table"
                                   data-url="{{ route('m.setting.translation.all') }}"
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
                                   data-cookie-id-table="tableTranslationGet"
                                   data-minimum-count-columns="2"
                                   data-pagination="true"
                                   data-page-list="[10, 25, 50, 100, ALL]"
                                   data-show-footer="false">
                                <thead>
                                <tr>
                                    <th data-field="key_lang" data-sortable="true">Ключ</th>
                                    <th data-field="trans" data-sortable="false">Название</th>
                                    <th data-field="id" data-formatter="editFormatter">Действия</th>
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
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.css') }}">
@endsection
@section('script')
    <script src="{{ asset('assets/js/bootstrap-table/dist/bootstrap-table.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/locale/bootstrap-table-ru-RU.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/cookie/bootstrap-table-cookie.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/reorder-rows/bootstrap-table-reorder-rows.min.js') }}"></script>
    <script src="{{ asset('assets/js/table_dnd/dist/jquery.tablednd.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert2.js') }}"></script>
    <script type="application/javascript">

        function editFormatter(value, row) {
            return '<button onclick="AdminTransPage.editTrans(' + value + ')" class="btn btn-success btn-xs m-r-5" type="button" data-original-title="Редактировать" title="Редактировать"><i class="icofont icofont-edit-alt"></i></button>';
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error : function(jqXHR, textStatus, errorThrown) {
                var errorMess = " Ошибка: " + textStatus + ": " + errorThrown
                if (jqXHR.status == 404) {
                    errorMess = "Не найденн урл для запроса";
                }

                $.notify({
                    title:'Ошибка',
                    message:errorMess,
                }, {
                    type:'danger',
                    mouse_over:true,
                });
            }
        });

        var AdminTransPage = {
            init: function () {
                AdminTransPage.Modal = $('#modalTrans');
                AdminTransPage.Loader = '<div class="modal-dialog modal-lg"><div class="modal-content">' +
                    '<div class="modal-body"><div class="loader-box"><div class="loader-39"></div></div></div></div></div>';
                AdminTransPage.table = $('#tableTranslationGet');
            },
            newTrans: function () {
                $.ajax({
                    method: "POST",
                    url: '/apiadmin/trans/new',
                    cache: false,
                    beforeSend: function() {
                        // setting a timeout
                        AdminTransPage.Modal.html(AdminTransPage.Loader);
                        AdminTransPage.Modal.modal('show');
                    },
                    success: function (res) {

                        var notyTitle = 'Ошибка';

                        if (res.action == 'success') {
                            AdminTransPage.Modal.html(res.html);
                        }
                        else {
                            $.notify({
                                title: notyTitle,
                                message:data.message,
                            }, {
                                type: data.action
                            });
                        }
                    }
                });
            },
            editTrans: function (idTrans) {
                $.ajax({
                    method: "POST",
                    url: '/apiadmin/trans/get/' + idTrans,
                    cache: false,
                    beforeSend: function() {
                        // setting a timeout
                        AdminTransPage.Modal.html(AdminTransPage.Loader);
                        AdminTransPage.Modal.modal('show');
                    },
                    success: function (res) {

                        var notyTitle = 'Ошибка';

                        if (res.action == 'success') {
                            AdminTransPage.Modal.html(res.html);
                            document.getElementById('formTransEdit').insertAdjacentHTML(
                                'beforeend',
                                '<input type="hidden" id="modalIsEditing" name="editingModal" value="true"/>'
                            )
                        }
                        else {
                            $.notify({
                                title: notyTitle,
                                message:data.message,
                            }, {
                                type: data.action
                            });
                        }
                    }
                });
            },
            saveTrans: function () {

                var thisData = $('#formTransEdit').serialize();

                $.ajax({
                    method: "POST",
                    url: '/apiadmin/trans/save',
                    data: thisData,
                    cache: false,
                    beforeSend: function() {
                        // setting a timeout
                        AdminTransPage.Modal.html(AdminTransPage.Loader);
                    },
                    success: function (res) {

                        var notyTitle = 'Ошибка';

                        if (res.action == 'success') {
                            AdminTransPage.Modal.html('').modal('hide');
                            AdminTransPage.table.bootstrapTable('refresh');
                        }
                        else {
                            AdminTransPage.Modal.html(res.html);
                        }

                        $.notify({
                            title: notyTitle,
                            message: res.message,
                        }, {
                            type: res.action
                        });
                    }
                });
            },
            deleteTrans: function (idTrans) {

            }
        };

        $(function () {
            'use strict';
            //jQuery code here
            AdminTransPage.init();
        });
    </script>
    <div id="modalTrans" class="modal fade bd-lang-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"></div>
@endsection