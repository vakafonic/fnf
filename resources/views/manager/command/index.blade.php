@extends('manager.app')
@section('title', 'Управление')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3><i data-feather="codepen"></i> Управление</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item active">Управление</li>
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
                        <h5 class="m-b-0">Управление</h5>
                    </div>
                    <div class="card-content">
                        <div class="row"></div>
                        <div class="m-10">
                            <div id="toolbarComand">
                                {{--<button type="button" onclick="" class="btn btn-primary "><span class="icon-plus"></span> Новая функция управления</button>--}}
                            </div>
                            <table role="grid" id="tableComandAll"
                                   class="table responsive_table dataTables_wrapper"
                                   {{--data-method="post"--}}
                                   data-toggle="table"
                                   data-url="{{ route('m.command.all') }}"
                                   data-id-field="id"
                                   data-toolbar="#toolbarComand"
                                   data-search="true"
                                   data-show-refresh="true"
                                   data-tree-show-field="title"
                                   data-sort-name="name"
                                   data-sort-order="asc"
                                   data-mobile-responsive="true"
                                   {{--data-reorderable-rows="true"--}}
                                   data-use-row-attr-func="true"
                                   data-cookie="true"
                                   data-cookie-id-table="tablecomand"
                                   data-minimum-count-columns="2"
                                   data-pagination="true"
                                   data-page-list="[10, 25, 50, 100, ALL]"
                                   data-show-footer="false">
                                <thead>
                                <tr>
                                    <th data-field="name" data-formatter="editNameFormatter">Название</th>
                                    <th data-field="icon" data-width="100" data-formatter="iconFormatter">Иконка</th>
                                    <th data-field="id" data-width="100" data-formatter="editFormatter">Действия</th>
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
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/tree-column/bootstrap-table-tree-column.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/reorder-rows/bootstrap-table-reorder-rows.min.js') }}"></script>
    <script src="{{ asset('assets/js/table_dnd/dist/jquery.tablednd.min.js') }}"></script>
    <script type="application/javascript">
        function editNameFormatter(value, row) {
            return '<strong class="m-t-5">' + value + '</strong><br/><small>' + row.trans + '</small>'
        }
        function iconFormatter(value, row) {
            return '<img class="height-50" src="/icon/' + value + '" alt="'
                + row.name + '" data-original-title="' + row.name + '" title="' + row.name + '">';
        }

        function editFormatter(value, row) {
            var edBut =  '<button type="button" onClick="AdminPageComand.edit('+ value + ')" class="btn btn-success btn-xs m-r-5" type="button" data-original-title="Редактировать" title="Редактировать"><i class="icofont icofont-edit-alt"></i></button>';
                return edBut;
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

        var AdminPageComand = {
            init: function () {
                this.Modal = $('#modalCommand');
                this.table = $('#tableComandAll');
                this.modalForm = $('#formCommandEdit');
                this.Loader = '<div class="modal-dialog modal-lg"><div class="modal-content">' +
                    '<div class="modal-body"><div class="loader-box"><div class="loader-39"></div></div></div></div></div>';
            },
            edit: function (idField) {
                $.ajax({
                    method: "POST",
                    url: '/command/edit/' + idField,
                    cache: false,
                    beforeSend: function() {
                        // setting a timeout
                        AdminPageComand.modalForm.html(AdminPageComand.Loader);
                        AdminPageComand.Modal.modal('show');
                    },
                    success: function (res) {

                        var notyTitle = 'Ошибка';

                        if (res.action == 'success') {
                            AdminPageComand.modalForm.html(res.html);
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
            save: function (thisId) {
                var thisData = AdminPageComand.modalForm.serialize();

                $.ajax({
                    method: "POST",
                    url: '/command/save/' + thisId,
                    data: thisData,
                    cache: false,
                    beforeSend: function() {
                        // setting a timeout
                        AdminPageComand.modalForm.html(AdminPageComand.Loader);
                    },
                    success: function (res) {

                        var notyTitle = 'Ошибка';

                        if (res.action == 'success') {
                            AdminPageComand.modalForm.html('');
                            AdminPageComand.Modal.modal('hide');
                            AdminPageComand.table.bootstrapTable('refresh');
                        }
                        else {
                            AdminPageComand.modalForm.html(res.html);
                        }

                        $.notify({
                            title: notyTitle,
                            message: res.message,
                        }, {
                            type: resaction
                        });
                    }
                });
            }
        };

        $(function () {
            'use strict';
            //jQuery code here
            AdminPageComand.init();
        });
    </script>
    <div id="modalCommand" class="modal fade bd-lang-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLangModalLabel">{{ !empty($buttonsPlay->id) ? 'Редактировать команду' : 'Создать новую команду' }}</h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                {{ Form::open(['class' => 'novalidate', 'id' => 'formCommandEdit']) }}
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection