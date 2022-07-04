@extends('manager.app')
@section('title', 'Все пользователи')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3><i class="icofont icofont-game-control"></i> Все игры</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item active">Все игры</li>
                    </ol>
                </div>
                <div class="col-lg-6">
                    <div class="bookmark pull-right">
                        <ul>
                            @if(Auth::user()->isRoleAction('games_create'))
                            <li><a  href="{{ route('m.games.create') }}" data-container="body" data-toggle="popover" data-placement="top" title="" data-original-title="Добавить новую игру"><i data-feather="plus"></i></a></li>
                            @endif
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
                        <h5 class="m-b-0">Список игр</h5><span>Управления играми для сайта </span>
                    </div>
                    <div class="card-content">
                        <div class="row">
                            <div class="col-md-3">
                                <div id="toolbarGames" class="p-10">
                                    <h5 class="m-t-20">Фильтр</h5>
                                    @if(Auth::user()->isRoleAction('games_viewonly'))
                                        {!! Form::hidden('user', Auth::user()->id) !!}
                                    @else
                                    <div class="form-group">
                                        <label for="user" class="form-label">Пользователь</label>
                                        {!! Form::select('user', $users_game , null , ['class' => 'form-control', 'id' => 'user_select']) !!}
                                    </div>
                                    @endif
                                    <div class="form-group">
                                        <label for="user" class="form-label">Публикация</label>
                                        {!! Form::select('public', [3 => 'Все', 0 => 'Не опубликован', 1 => 'Опубликован'], null , ['class' => 'form-control', 'id' => 'public_select']) !!}
                                    </div>
                                    <div class="form-group">
                                        <label>Категории</label>
                                        {!! Form::hidden('genre_add', '', ['id' => 'genre_add']) !!}
                                        <div id="treecheckbox">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="m-10">
                                    <table role="grid" id="tableGamesAll"
                                           class="table responsive_table dataTables_wrapper"
                                           {{--data-method="post"--}}
                                           data-toggle="table"
                                           data-url="{{ route('m.games.all') }}"
                                           data-id-field="id"
                                           {{--data-toolbar="#toolbarGames"--}}
                                           data-query-params="queryParams"
                                           data-search="true"
                                           data-search-time-out="1500"
                                           data-show-refresh="true"
                                           data-tree-show-field="title"
                                           data-sort-name="created_at"
                                           data-sort-order="desc"
                                           data-detail-view="true"
                                           data-detail-formatter="detailFormatter"
                                           data-mobile-responsive="true"
                                           data-reorderable-rows="true"
                                           data-use-row-attr-func="true"
                                           data-cookie="true"
                                           data-cookie-id-table="tableGames{{Auth::user()->id}}"
                                           data-side-pagination="server"
                                           data-pagination="true"
                                           data-page-list="[10, 25, 50, 100, ALL]"
                                           data-show-footer="false">
                                        <thead>
                                        <tr>
                                            <th data-field="name" data-sortable="true" data-formatter="editNameFormatter">Название</th>
                                            <th data-field="created_at" data-sortable="true" data-align="center" data-formatter="createdAtFormatter" data-width="120">Дата</th>
                                            <th data-field="created_by" data-sortable="true" data-align="center" data-formatter="createdFormatter">Добавлена</th>
                                            <th data-field="public" data-sortable="true" data-align="center" data-width="120" data-formatter="publicFormatter">Опубликован</th>
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
        </div>
    </div>
@endsection
@section('stylesheet')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/bootstrap-table/dist/bootstrap-table.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/bootstrap-table/dist/extensions/tree-column/bootstrap-table-tree-column.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/select2/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/tree.css') }}">
    <style>
        .fixed-table-toolbar .search {
            width: 85%;
        }
        .fixed-table-toolbar .search:after {
            font-family: 'IcoFont' !important;
            speak: none;
            font-style: normal;
            font-weight: normal;
            font-variant: normal;
            text-transform: none;
            line-height: 1;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-size: 14px;
            content: "\ed2b";
            position: absolute;
            right: 12px;
            top: 12px;
            cursor: pointer;
        }
    </style>
@endsection
@section('script')
    <script src="{{ asset('assets/js/bootstrap-table/dist/bootstrap-table.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/locale/bootstrap-table-ru-RU.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/cookie/bootstrap-table-cookie.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/tree-column/bootstrap-table-tree-column.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/reorder-rows/bootstrap-table-reorder-rows.min.js') }}"></script>
    <script src="{{ asset('assets/js/table_dnd/dist/jquery.tablednd.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/js/tree/jstree.min.js') }}"></script>
    <script type="application/javascript">
        var $thisUser = {{ Auth::user()->id }};
        function queryParams(params) {
            params.user = $('select[name="user"]').val();
            params.public = $('select[name="public"]').val();
            params.genre_add = $('input[name="genre_add"]').val();
            return params;
        }
        function createdAtFormatter(value, row) {
            return row.created_at_format;
        }
        function createdFormatter(value, row) {
            return row.user_name;
        }
        function editNameFormatter(value, row) {
            return '<img class="b-r-8 img-50 pull-left m-r-10" src="'
                + (row.image != null && row.image.length > 3 ? '{{ Storage::disk('images')->url('') }}' + row.image : '{{ config('site.game.image.default') }}')
                + '" alt="'
                + value + '" data-original-title="' + value + '" title="' + value + '"><strong class="m-t-5" style="display: block;">' + value + '</strong><small>' + row.sub_name + '</small>'
        }
        function editFormatter(value, row) {
            var edBut = '';
            @if(Auth::user()->isRoleAction('games_edit'))
                edBut +=  '<a href="/games/edit/'
                    + value + '"  class="btn btn-success btn-xs m-r-5" type="button" data-original-title="Редактировать" title="Редактировать"><i class="icofont icofont-edit-alt"></i></a>';
            @elseif(Auth::user()->isRoleAction('games_editonly'))
            if($thisUser == row.created_by) {
                edBut +=  '<a href="/games/edit/'
                    + value + '"  class="btn btn-success btn-xs m-r-5" type="button" data-original-title="Редактировать" title="Редактировать"><i class="icofont icofont-edit-alt"></i></a>';
            }
            @endif
            @if(Auth::user()->isRoleAction('games_delete'))
            edBut += '<button class="btn btn-danger btn-xs sweet-delete m-r-5" onclick="AdminGamesPage.delete(' + value + ')" type="button" data-original-title="Удалить" title="Удалить"><i class="icofont icofont-delete-alt"></i></button>';
            @elseif(Auth::user()->isRoleAction('games_deleteonly'))
            if($thisUser == row.created_by) {
                edBut += '<button class="btn btn-danger btn-xs sweet-delete m-r-5" onclick="AdminGamesPage.delete(' + value + ')" type="button" data-original-title="Удалить" title="Удалить"><i class="icofont icofont-delete-alt"></i></button>';
            }
            @endif

                edBut +=  '<a target="_blank" href="{{ config('app.url') }}ru/'
                + row.url + '/"  class="btn btn-info btn-xs m-r-5" type="button" data-original-title="Просмотреть" title="Просмотреть"><i class="icofont icofont-eye-alt"></i></a>';
            return edBut;
        }
        function publicFormatter(value, row, index) {
            @if(Auth::user()->role == 1 || Auth::user()->isRoleAction('games_public'))
            return '<div class="media-body text-center icon-state switch-outline">\n' +
                '<label class="switch">\n' +
                '<input name="check_public" value="' + row.id + '" type="checkbox" ' +
                (value == 1 ? 'checked=""' : '') +
                '><span class="switch-state bg-primary"></span>\n' +
                '</label>\n' +
                '</div>';
            @endif
            return value == 1 ? '<span class="txt-primary">Да</span>' : '<span class="txt-warning">Нет</span>';
        }
        function detailFormatter(index, row) {
            return '<div id="details-' + row.id + '" style="margin: 0 20px;">' +
                '<p class="text-center"><i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i> Пожалуйста, подождите! Загрузка...</p></div>';
        }

        $('#tableGamesAll').on('expand-row.bs.table', function (index, row, detail) {

            $.ajax({
                type: "POST",
                url: '/games/details/' + detail.id,
                cache: false,
                success: function(data)
                {
                    if (data.success) {
                        $('#details-' + detail.id).html(data.html);
                    }
                }
            });
        });
        
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

        let cooliesUser = Cookies.get('admin_game_page.user');
        if (typeof cooliesUser != "undefined" && cooliesUser > 0) {
            $('select[name="user"]').val(cooliesUser);
        }

        let cooliesPublic = Cookies.get('admin_game_page.public');
        if (typeof cooliesPublic != "undefined" && cooliesPublic < 3) {
            $('select[name="public"]').val(cooliesPublic);
        }

        const tree = $('#treecheckbox');
        const ControllTwoGame = {!! \App\Models\Genre::where('for_two', 1)->pluck('id') !!};

        tree.jstree({
            "checkbox": { "three_state": false },
            'core': {
                'data': {!! $tree !!}
            },
            "plugins": ["checkbox"],
            'tie_selection': false
        });

        const seltree = Cookies.get('admin_game_page.tree');

        tree.on('loaded.jstree', function (e, data) {

            if (typeof seltree != "undefined") {
                $('input#genre_add').val(seltree);
                seltreearray = seltree.split(',');
                seltreearray.forEach(function(entry) {
                    tree.jstree('select_node', parseInt(entry));
                });
            };
        });

        const $table = $('#tableGamesAll');
        var startPage = 0;

        const AdminGamesPage = {
            init: function () {

                $('.fixed-table-toolbar .search').on('click', function (e) {
                    //console.log([e.offsetX, e.target.offsetLeft]);
                    if (e.target.offsetLeft > 0) {
                        $table.bootstrapTable('resetSearch');
                    }
                });

                $('select[name="user"]').on('change', function() {
                    $table.bootstrapTable('refresh');
                    Cookies.set('admin_game_page.user', this.value);
                });

                $('select[name="public"]').on('change', function() {
                    $table.bootstrapTable('refresh');
                    Cookies.set('admin_game_page.public', this.value);
                });

                tree.on('changed.jstree', function (e, data) {
                    if(jQuery.inArray(parseInt(data.node.id), ControllTwoGame) !== -1){
                        if (data.action == "select_node") {
                            $('.section-command-2').show();
                        } else {
                            $('.section-command-2').hide();
                        }
                    }

                    let valueTree = data.selected.join(",");

                    $('input#genre_add').val(valueTree);
                    Cookies.set('admin_game_page.tree', valueTree);
                    $table.bootstrapTable('refresh');
                });

                $('#user_select').select2({
                    language: "ru",
                });

                $table.on('load-success.bs.table', function (e, order) {

                    if (startPage == 0) {
                        startPage++;
                        if (typeof seltree != "undefined") {
                            $table.bootstrapTable('refresh');
                        }
                    };

                    $('[data-toggle="tooltip"]').tooltip();

                    $('input[name="check_public"]').change(function() {
                        $.ajax({
                            type: "POST",
                            url: '/games/public/' + $(this).val() + '/' + (this.checked ? 1 : 0),
                            cache: false,
                            success: function(data)
                            {
                                if (data.success) {
                                   // $table.bootstrapTable('refresh');
                                }
                            }
                        });
                    });
                });
            },
            delete: function (idGame) {
                Swal.fire({
                    title: 'Вы уверенны?',
                    text: "Вы действительно хотите удалить эту игру?!",
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
                            url: '/games/delete/' + idGame,
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
            AdminGamesPage.init();
        });
    </script>
@endsection