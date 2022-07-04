@extends('manager.app')
@section('title', 'Все жанры')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3><i class="icofont icofont-angry-monster"></i> Все жанры</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item active">Все жанры</li>
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
                        <h5 class="m-b-0">Список жанров</h5><span>Управления жанрами для сайта<br>Для сортировки, просто перетяните запись в нужный ряд</span>
                    </div>
                    <div class="card-content">
                        <div class="row"></div>
                        <div class="m-10">
                            <div id="toolbarGenre">
                                <a href="{{ route('m.games.genres.new') }}" class="btn btn-primary "><span class="icon-plus"></span> Новый жанр</a>
                            </div>
                            <table id="tableGenresAll" class="table responsive_table dataTables_wrapper"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('stylesheet')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/bootstrap-table/dist/bootstrap-table.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/bootstrap-table/dist/extensions/treegrid/css/jquery.treegrid.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/select2/css/select2.min.css') }}">
@endsection
@section('script')
    <script src="{{ asset('assets/js/bootstrap-table/dist/bootstrap-table.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/locale/bootstrap-table-ru-RU.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/cookie/bootstrap-table-cookie.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/reorder-rows/bootstrap-table-reorder-rows.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/treegrid/bootstrap-table-treegrid.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/treegrid/js/jquery.treegrid.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/js/table_dnd/dist/jquery.tablednd.min.js') }}"></script>
    <script type="application/javascript">
        var $table = $('#tableGenresAll');

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

        function mounted() {
            $table.bootstrapTable({
                url: '{{ route('m.games.genres.all') }}',
                striped: true,
                reorderableRows: true,
                useRowAttrFunc:true,
                cookie: true,
                cookieIdTable: 'tableGenresAll',
                showRefresh: true,
                mobileResponsive: true,
                toolbar: '#toolbarGenre',
                sidePagination: 'server',
                idField: 'id',
                columns: [
                    {
                        field: 'sort',
                        title: 'Сорт',
                        width: 50,
                        sortable: true,
                    },
                    {
                        field: 'image',
                        title: 'Image',
                        width: 100,
                        formatter: 'imageFormatter'
                    },
                    {
                        field: 'value',
                        title: 'Название',
                        sortable: true,
                    },
                    /*{
                        field: 'url',
                        title: 'URL алиас',
                        sortable: true,
                    },*/
                    {
                        field: 'show_menu',
                        title: 'Меню "Все игры"',
                        width: 120,
                        align: 'center',
                        sortable: true,
                        formatter: 'menuFormatter'
                    },
                    {
                        field: 'show_menug',
                        title: 'Меню "Жанры"',
                        width: 120,
                        align: 'center',
                        sortable: true,
                        formatter: 'menuFormatterg'
                    },
                    {
                        field: 'public',
                        title: 'Опубликован',
                        width: 120,
                        align: 'center',
                        sortable: true,
                        formatter: 'publicFormatter'
                    },
                    {
                        field: 'count_game',
                        title: 'Игр',
                        width: 80,
                        align: 'center',
                        sortable: false,
                    },
                    {
                        field: 'id',
                        title: 'Действия',
                        width: 180,
                        align: 'center',
                        formatter: 'operateFormatter'
                    }
                ],
                treeShowField: 'value',
                parentIdField: 'pid',
                onLoadSuccess: function(data) {
                    $table.treegrid({
                        treeColumn: 2,
                        onChange: function() {
                            $table.bootstrapTable('resetWidth');
                        }
                    });
                    $('[data-toggle="tooltip"]').tooltip();

                    $('input[name="check_public"]').change(function() {
                        $.ajax({
                            type: "POST",
                            url: '/games/genres/public/' + $(this).val() + '/' + (this.checked ? 1 : 0),
                            cache: false,
                            success: function(data)
                            {
                                if (data.success) {
                                    //$table.bootstrapTable('refresh');
                                }
                            }
                        });
                    });

                    $('input[name="check_menu"]').change(function() {
                        $.ajax({
                            type: "POST",
                            url: '/games/genres/show_menu/' + $(this).val() + '/' + (this.checked ? 1 : 0),
                            cache: false,
                            success: function(data)
                            {
                                if (data.success) {
                                    //$table.bootstrapTable('refresh');
                                }
                            }
                        });
                    });

                    $('input[name="check_menug"]').change(function() {
                        $.ajax({
                            type: "POST",
                            url: '/games/genres/show_menug/' + $(this).val() + '/' + (this.checked ? 1 : 0),
                            cache: false,
                            success: function(data)
                            {
                                if (data.success) {
                                    //$table.bootstrapTable('refresh');
                                }
                            }
                        });
                    });
                },
                onReorderRow: function (order) {
                    var dataSend = [];
                    $.each(order, function (i, value) {
                        dataSend.push(value.id);
                    });

                    $.post( "{{ route('m.games.genres.sort') }}", { data: dataSend })
                        .done(function( data ) {
                            $table.bootstrapTable('refresh');
                        });
                }
            })
        }
        /*$table.on('reorder-row.bs.table', function (e, order) {
            console.log('test2');
            console.log(order)
            $.each(order, function (i, value) {
                console.log(value.denominazione, value.unique)
            });
        })*/
        function imageFormatter(value, row, index) {
            return '<img class="img-50 b-r-15 pull-left m-r-10" src="' + value + '" alt="' + row.name + '" data-original-title="" title="' + row.name + '">';
        }
        function operateFormatter(value, row, index) {

            var btDel = '<a href="/games/genres/edit/' + value + '" class="btn btn-success btn-xs m-r-5" type="button" data-toggle="tooltip" data-original-title="Редактировать" title="Редактировать">' +
                '<i class="icofont icofont-edit-alt"></i></a>';
            @if(Auth::user()->role == 1)
                btDel += '<button class="btn btn-danger btn-xs sweet-delete" onclick="AdminGenresPage.delete(' + value + ')" type="button" data-toggle="tooltip" data-original-title="Удалить" title="Удалить"><i class="icofont icofont-delete-alt"></i></button>';
            @endif
                btDel += '<a target="_blank" href="{{ config('app.url') }}ru/' + row.url +'/" class="btn btn-info btn-xs  m-l-5" type="button" data-toggle="tooltip" data-original-title="Просмотреть" title="Просмотреть"><i class="icofont icofont-eye-alt"></i></a>';
                return btDel;
        }

        function publicFormatter(value, row, index) {
            return '<div class="media-body text-right icon-state switch-outline">\n' +
                '<label class="switch">\n' +
                '<input name="check_public" value="' + row.id + '" type="checkbox" ' +
                (value == 1 ? 'checked=""' : '') +
                '><span class="switch-state bg-primary"></span>\n' +
                '</label>\n' +
                '</div>';
        }

        function menuFormatter(value, row, index) {
            return '<div class="media-body text-right icon-state switch-outline">\n' +
                '<label class="switch">\n' +
                '<input name="check_menu" value="' + row.id + '" type="checkbox" ' +
                (value == 1 ? 'checked=""' : '') +
                '><span class="switch-state bg-warning"></span>\n' +
                '</label>\n' +
                '</div>';
        }

        function menuFormatterg(value, row, index) {
            return '<div class="media-body text-right icon-state switch-outline">\n' +
                '<label class="switch">\n' +
                '<input name="check_menug" value="' + row.id + '" type="checkbox" ' +
                (value == 1 ? 'checked=""' : '') +
                '><span class="switch-state bg-warning"></span>\n' +
                '</label>\n' +
                '</div>';
        }

        var AdminGenresPage = {
            init: function () {
                AdminGenresPage.Modal = $('#modalGenre');
                AdminGenresPage.Loader = '<div class="modal-dialog modal-lg"><div class="modal-content">' +
                    '<div class="modal-body"><div class="loader-box"><div class="loader-39"></div></div></div></div></div>';
            },
            delete: function (rowId) {
                Swal.fire({
                    title: 'Вы уверенны?',
                    text: "Вы действительно хотите удалить этот жанр?!",
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
                            url: '/games/genres/delete/' + rowId,
                            cache: false,
                            success: function (res) {

                                if (res.success) {
                                    $table.bootstrapTable('refresh');

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
                            }
                        });
                    }
                })
            }
        };

        $(function () {
            'use strict';
            //jQuery code here
            AdminGenresPage.init();
            mounted();
        });
    </script>
    <div id="modalGenre" class="modal fade bd-lang-modal-lg" tabindex="-1" role="dialog" aria-labelledby="genreModalLabel" aria-hidden="true"></div>
@endsection