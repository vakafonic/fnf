@extends('manager.app')
@section('title', 'Разработчики')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3><i data-feather="codepen"></i> Все разработчики</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item active">Все разработчики</li>
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
                        <h5 class="m-b-0">Список разработчиков</h5><span>Управления разработчиками для сайта</span>
                    </div>
                    <div class="card-content">
                        <div class="row"></div>
                        <div class="m-10">
                            <div id="toolbarHeroes">
                                <button type="button" onclick="AdminDeveloperPage.edit()" class="btn btn-primary "><span class="icon-plus"></span> Новый разработчик</button>
                            </div>
                            <table role="grid" id="tableDevelopersAll"
                                   class="table responsive_table dataTables_wrapper"
                                   {{--data-method="post"--}}
                                   data-toggle="table"
                                   data-url="{{ route('m.developers.all') }}"
                                   data-id-field="id"
                                   data-toolbar="#toolbarHeroes"
                                   data-search="true"
                                   data-show-refresh="true"
                                   data-tree-show-field="title"
                                   data-sort-name="name"
                                   data-sort-order="asc"
                                   data-mobile-responsive="true"
                                   {{--data-reorderable-rows="true"--}}
                                   data-use-row-attr-func="true"
                                   data-cookie="true"
                                   data-cookie-id-table="tabledevelopers"
                                   data-minimum-count-columns="2"
                                   data-pagination="true"
                                   data-page-list="[10, 25, 50, 100, ALL]"
                                   data-show-footer="false">
                                <thead>
                                <tr>
                                    <th data-field="name" data-sortable="true">Название</th>
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
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.css') }}">
@endsection
@section('script')
    <script src="{{ asset('assets/js/bootstrap-table/dist/bootstrap-table.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/locale/bootstrap-table-ru-RU.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/cookie/bootstrap-table-cookie.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/tree-column/bootstrap-table-tree-column.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/reorder-rows/bootstrap-table-reorder-rows.min.js') }}"></script>
    <script src="{{ asset('assets/js/table_dnd/dist/jquery.tablednd.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert2.js') }}"></script>
    <script type="application/javascript">

        function editFormatter(value, row) {
            var edBut = '';
            @if(Auth::user()->isRoleAction('devel_edit'))
            edBut +=  '<button onclick="AdminDeveloperPage.edit(' + value + ')" class="btn btn-success btn-xs m-r-5" type="button" data-original-title="Редактировать" title="Редактировать"><i class="icofont icofont-edit-alt"></i></button>';
            @endif
            @if(Auth::user()->role == 1)
                edBut += '<button class="btn btn-danger btn-xs sweet-delete" onclick="AdminDeveloperPage.delete(' + value + ')" type="button" data-original-title="Удалить" title="Удалить"><i class="icofont icofont-delete-alt"></i></button>';
            @endif
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

        var AdminDeveloperPage = {
            init: function () {
                AdminDeveloperPage.modalDeveloper = $('#modalDeveloper');
                AdminDeveloperPage.loader = '<div class="modal-dialog modal-lg"><div class="modal-content">' +
                    '<div class="modal-body"><div class="loader-box"><div class="loader-39"></div></div></div></div></div>';
            },
            edit: function (devId) {
                var url = '{{ route('m.developers.new') }}';

                if (typeof devId !== 'undefined') {
                    url = '/developers/edit/' + devId;
                }

                $.ajax({
                    method: "POST",
                    url: url,
                    cache: false,
                    beforeSend: function() {
                        // setting a timeout
                        AdminDeveloperPage.modalDeveloper.html(AdminDeveloperPage.loader).modal('show');
                    },
                    success: function (res) {

                        var notyTitle = 'Ошибка';

                        if (res.action == 'success') {
                            AdminDeveloperPage.modalDeveloper.html(res.html);
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
            save: function (thisForm) {
                var thisData = $(thisForm).parents('form').serialize();

                $.ajax({
                    method: "POST",
                    url: '{{ route('m.developers.save') }}',
                    data: thisData,
                    cache: false,
                    beforeSend: function() {
                        // setting a timeout
                        AdminDeveloperPage.modalDeveloper.html(AdminDeveloperPage.loader).modal('show');
                    },
                    success: function (res) {

                        var notyTitle = 'Ошибка';

                        AdminDeveloperPage.modalDeveloper.html(res.html);

                        if (res.action == 'success') {
                            notyTitle = 'Готово';
                            AdminDeveloperPage.modalDeveloper.modal('hide');
                            $('#tableDevelopersAll').bootstrapTable('refresh');
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
            delete: function (devId) {
                Swal.fire({
                    title: 'Вы уверенны?',
                    text: "Вы действительно хотите удалить этого разработчика?!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonText: 'Отменить',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Да, удалить!'
                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            method: "POST",
                            url: '/developers/delete/' + devId,
                            cache: false,
                            success: function (res) {

                                if (res.result) {
                                    $('#tableDevelopersAll').bootstrapTable('refresh');

                                    Swal.fire(
                                        'Готово!',
                                        res.message,
                                        'success'
                                    )
                                } else {
                                    Swal.fire(
                                        'Удаление!',
                                        res.message,
                                        'error'
                                    )
                                }

                                //Swal.close();

                            }
                        });


                    }
                })
            }
        };

        $(function () {
            'use strict';
            //jQuery code here
            AdminDeveloperPage.init();
        });
    </script>
    <div id="modalDeveloper" class="modal fade bd-lang-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"></div>
@endsection