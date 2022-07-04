@extends('manager.app')
@section('title', 'Все герои')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3><i class="icofont icofont-bow"></i> Все герои</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item active">Все герои</li>
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
                        <h5 class="m-b-0">Список героев</h5><span>Управления героями для сайта <br>
                            Для сортировки просто перетяните запись в таблице выше или ниже</span>
                    </div>
                    <div class="card-content">
                        <div class="row"></div>
                        <div class="m-10">
                            <div id="toolbarHeroes">
                                <a href="{{ route('m.games.heroes.get.new') }}" class="btn btn-primary "><span class="icon-plus"></span> Новый герой</a>
                            </div>
                            <table role="grid" id="tableHeroesAll"
                                   class="table responsive_table dataTables_wrapper"
                                   {{--data-method="post"--}}
                                   data-toggle="table"
                                   data-url="{{ route('m.games.heroes.all') }}"
                                   data-id-field="id"
                                   data-toolbar="#toolbarHeroes"
                                   data-search="true"
                                   data-show-refresh="true"
                                   data-tree-show-field="title"
                                   data-sort-name="sort"
                                   data-sort-order="asc"
                                   data-mobile-responsive="true"
                                   data-reorderable-rows="true"
                                   data-use-row-attr-func="true"
                                   data-cookie="true"
                                   data-cookie-id-table="tableHeroes"
                                   data-minimum-count-columns="2"
                                   {{--data-pagination="true"
                                   data-page-list="[10, 25, 50, 100, ALL]"--}}
                                   data-show-footer="false">
                                <thead>
                                <tr>
                                    <th data-field="value" data-sortable="true" data-formatter="editNameFormatter">Название</th>
                                    <th data-field="show_menu" data-sortable="true" data-align="center" data-width="80" data-formatter="menuFormatter">В меню</th>
                                    <th data-field="public" data-sortable="true" data-align="center" data-width="120" data-formatter="publicFormatter">Опубликован</th>
                                    <th data-field="count_game" data-sortable="true" data-align="center" data-width="80" data-align="center" data-sortable="false">Игр</th>
                                    <th data-field="id" data-width="180" data-formatter="editFormatter">Действия</th>
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
            var edBut =  '<a href="/games/heroes/edit/'
                + value + '"  class="btn btn-success btn-xs m-r-5" type="button" data-original-title="Редактировать" title="Редактировать"><i class="icofont icofont-edit-alt"></i></a>';

            @if(Auth::user()->role == 1)
                edBut += '<button class="btn btn-danger btn-xs sweet-delete" onclick="AdminHeroesPage.delete(' + value + ')" type="button" data-original-title="Удалить" title="Удалить"><i class="icofont icofont-delete-alt"></i></button>';
            @endif
                edBut += '<a target="_blank" href="{{ config('app.url') }}ru/' + row.url +'/" class="btn btn-info btn-xs m-l-5" type="button" data-toggle="tooltip" data-original-title="Просмотреть" title="Просмотреть"><i class="icofont icofont-eye-alt"></i></a>';
            return edBut;
        }
        function editNameFormatter(value, row) {
            return '<img class="b-r-8 img-50 pull-left m-r-10" src="' + row.image + '" alt="'
                + value + '" data-original-title="' + value + '" title="' + value + '"><strong class="m-t-5">' + value + '</strong><br/><small>' + row.trans + '</small>'
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

        var $table = $('#tableHeroesAll');

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

        var AdminHeroesPage = {
            init: function () {
                $table.on('reorder-row.bs.table', function (e, order) {
                    var dataSend = [];
                    $.each(order, function (i, value) {
                        dataSend.push(value.id);
                    });

                    $.post( "{{ route('m.games.heroes.sort') }}", { data: dataSend })
                        .done(function( data ) {
                            $table.bootstrapTable('refresh');
                        });
                });

                $table.on('load-success.bs.table', function (e, order) {

                    $('[data-toggle="tooltip"]').tooltip();

                    $('input[name="check_public"]').change(function() {
                        $.ajax({
                            type: "POST",
                            url: '/games/heroes/public/' + $(this).val() + '/' + (this.checked ? 1 : 0),
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
                            url: '/games/heroes/show_menu/' + $(this).val() + '/' + (this.checked ? 1 : 0),
                            cache: false,
                            success: function(data)
                            {
                                if (data.success) {
                                    //$table.bootstrapTable('refresh');
                                }
                            }
                        });
                    });

                })

                AdminHeroesPage.modalHeros = $('#modalHeros');
                AdminHeroesPage.table = $('#tableHeroesAll')
                AdminHeroesPage.loader = '<div class="modal-dialog modal-lg"><div class="modal-content">' +
                    '<div class="modal-body"><div class="loader-box"><div class="loader-39"></div></div></div></div></div>';
            },
            delete: function (rowId) {
                Swal.fire({
                    title: 'Вы уверенны?',
                    text: "Вы действительно хотите удалить этого героя?!",
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
                            url: '/games/heroes/delete/' + rowId,
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
            AdminHeroesPage.init();
        });
    </script>
    <div id="modalHeros" class="modal fade bd-lang-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"></div>
@endsection