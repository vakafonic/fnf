@extends('manager.app')
@section('title', 'Языки')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3><i class="icofont icofont-racing-flag-alt"></i> Языки</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item active">Языки</li>
                    </ol>
                </div>
                <div class="col-lg-6">
                    <div class="bookmark pull-right">
                        <ul>
                            <li><a href="javascript:void(0)" onclick="AdminLangPAge.newLang()" data-container="body" data-toggle="popover" data-placement="top" title="" data-original-title="Добавить "><i data-feather="plus"></i></a></li>
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
                        <h5 class="m-b-0">Список языков</h5><span>Управления языками для сайта <br>
                            Для сортировки просто перетяните запись в таблице выше или ниже</span>
                    </div>
                    <div class="card-content">
                        <div class="row"></div>
                        <div class="m-10">
                            <table role="grid" id="tableLangAll"
                                   class="table responsive_table dataTables_wrapper"
                                   {{--data-method="post"--}}
                                   data-toggle="table"
                                   data-url="{{ route('m.setting.language.all') }}"
                                   data-id-field="id"
                                   data-search="true"
                                   data-show-refresh="true"
                                   {{--data-parent-idfield="parent_id"
                                   data-root-parent-id="0"--}}
                                   data-tree-show-field="title"
                                   data-sort-name="sort"
                                   data-sort-order="asc"
                                   data-mobile-responsive="true"
                                   data-reorderable-rows="true"
                                   data-use-row-attr-func="true"
                                   data-cookie="true"
                                   data-cookie-id-table="tableTranslation"
                                   data-minimum-count-columns="2"
                                   data-pagination="true"
                                   data-page-list="[10, 25, 50, 100, ALL]"
                                   data-show-footer="false">
                                <thead>
                                <tr>
                                    <th data-field="name" data-sortable="true" data-formatter="nameFormatter">Язык</th>
                                    <th data-field="code" data-sortable="true">Код</th>
                                    <th data-field="status" data-formatter="statusFormatter" data-align="center">Статус</th>
                                    <th data-field="sort" data-sortable="true">Сортировки</th>
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
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/tree-column/bootstrap-table-tree-column.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/reorder-rows/bootstrap-table-reorder-rows.min.js') }}"></script>
    <script src="{{ asset('assets/js/table_dnd/dist/jquery.tablednd.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert2.js') }}"></script>
    <script type="application/javascript">
        function nameFormatter(value, row) {
            if (row.main == 1) {
                return value + ' <small>(по умолчанию)</small>';
            } else {
                return value;
            }
        }
        function statusFormatter(value, row) {
            if (value == 1) {
                return '<span class="badge flat-badge-success">активный</span>';
            }

            return '<span class="badge flat-badge-secondary">не активный</span>';
        }

        function editFormatter(value, row) {
            button = '<button onclick="AdminLangPAge.editLang(' + value + ')" class="btn btn-success btn-xs m-r-5" type="button" data-original-title="Редактировать" title="Редактировать"><i class="icofont icofont-edit-alt"></i></button>';

            if(row.main != 1) {
                button += '<button class="btn btn-danger btn-xs sweet-delete" onclick="AdminLangPAge.deleteLang(' + value + ')" type="button" data-original-title="Удалить" title="Удалить"><i class="icofont icofont-delete-alt"></i></button>';
            }

            return button;
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

        var AdminLangPAge = {
            init: function () {
                AdminLangPAge.table = $('#tableLangAll');
                AdminLangPAge.langModal = $('.bd-lang-modal-lg');
                AdminLangPAge.formLangEdit = $('#formLangEdit');

                $('input[name="main"]').change(function() {
                    if(this.checked) {
                        $('input[name="status"]').prop( "checked", true );
                    }
                });

                $('input[name="status"]').change(function() {
                    if(!this.checked) {
                        $('input[name="main"]').prop( "checked", false );
                    }
                });

                AdminLangPAge.table.on('reorder-row.bs.table', function (e, order) {
                    var dataSend = [];
                    $.each(order, function (i, value) {
                        dataSend.push(value.id);
                    });

                    $.post( "{{ route('m.setting.language.sort') }}", { data: dataSend })
                        .done(function( data ) {
                            AdminLangPAge.table.bootstrapTable('refresh');
                        });
                })

                AdminLangPAge.formLangEdit.submit(function(e) {
                    e.preventDefault(); // avoid to execute the actual submit of the form.

                    var form = $(this);
                    var url = form.attr('action');

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: form.serialize(), // serializes the form's elements.
                        cache: false,
                        success: function(data)
                        {
                            var notyTitle = 'Сохранение';
                            if (data.action == 'danger') {
                                notyTitle = 'Ошибка';
                            } else {
                                AdminLangPAge.langModal.modal('hide');
                            }

                            AdminLangPAge.table.bootstrapTable('refresh');

                            $.notify({
                                title: notyTitle,
                                message:data.message,
                            }, {
                                type: data.action
                            });
                        }
                    });
                });
            },
            newLang: function () {
                AdminLangPAge.clearForm();
                $('#myLangModalLabel').text('Новый язык');
                AdminLangPAge.langModal.modal('show');
            },
            clearForm: function () {
                AdminLangPAge.formLangEdit[0].reset();
                $('.checkMainStatus').show();
                $('.checkActiveStatus').show();
            },
            editLang: function (langId) {
                AdminLangPAge.clearForm();
                $.ajax({
                    method: "POST",
                    url: '/apiadmin/lang/getedit/' + langId,
                    cache: false,
                    success: function (res) {

                        if (res) {
                            var langForm = AdminLangPAge.formLangEdit;
                            $('#myLangModalLabel').text('Редактировать язык');
                            langForm.find('input[name="id"]').val(res.id);
                            langForm.find('input[name="name"]').val(res.name);
                            langForm.find('input[name="code"]').val(res.code);
                            langForm.find('input[name="sort"]').val(res.sort);
                            langForm.find('input[name="locale"]').val(res.locale);
                            if(res.status == 1) {
                                langForm.find('input[name="status"]').prop( "checked", true );
                            }
                            if(res.main == 1) {
                                langForm.find('input[name="main"]').prop( "checked", true );
                                $('.checkMainStatus').hide();
                                $('.checkActiveStatus').hide();
                            }

                            AdminLangPAge.langModal.modal('show');
                        } else {
                            $.notify({
                                title:'Ошибка',
                                message:'Произошла ошибка получения данных',
                            }, {
                                type:'danger',
                                mouse_over:true,
                            });
                        }

                        //Swal.close();

                    }
                });
            },
            deleteLang: function (langId) {
                Swal.fire({
                    title: 'Вы уверенны?',
                    text: "Вы действительно хотите удалить этот язык?!",
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
                            url: '/apiadmin/lang/delete/' + langId,
                            cache: false,
                            success: function (res) {

                                if (res.action == 'success') {
                                    AdminLangPAge.table.bootstrapTable('refresh');

                                    Swal.fire(
                                        'Удаление!',
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
            AdminLangPAge.init();
        });

    </script>
    <div class="modal fade bd-lang-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLangModalLabel"></h4>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                {{ Form::open(['class' => 'novalidate', 'id' => 'formLangEdit']) }}
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" value="">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="name"> Название <span class="text-danger">*</span></label>
                                {{ Form::text('name', old('name'), ['placeholder' => 'Название', 'id' => 'name', 'class' => 'form-control', 'maxlength' => 100]) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="name"> Код <span class="text-danger">*</span></label>
                                {{ Form::text('code', old('code'), ['placeholder' => 'en', 'id' => 'code', 'class' => 'form-control', 'maxlength' => 2]) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="name"> Порядок сортировки</label>
                                {{ Form::number('sort', old('sort'), ['id' => 'sort', 'class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label" for="name"> Кодировка <span class="text-danger">*</span> </label>
                                {{ Form::text('locale', old('locale'), ['placeholder' => 'en_US.UTF-8,en_US,en-gb,en_gb,english', 'id' => 'code', 'class' => 'form-control', 'maxlength' => 100]) }}
                                <p class="help-block">Пример: en_US.UTF-8,en_US,en-gb,en_gb,english</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="checkbox checkbox-primary checkActiveStatus">
                                {{ Form::checkbox('status', 1, old('status', 0) == 1, ['id' => 'status']) }}
                                <label for="status">Активный</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="checkbox checkbox-primary checkMainStatus">
                                {{--{{ Form::checkbox('main', 1, old('main', 0) == 1, ['id' => 'main']) }}
                                <label for="main">По умолчанию</label>--}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary" type="button" data-dismiss="modal" data-original-title="" title="">Закрыть</button>
                    <button class="btn btn-success" type="submit">Сохранить</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection