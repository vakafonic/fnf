@extends('manager.app')
@section('title', 'Управление')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3><i data-feather="codepen"></i> Комментарии</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item active">Комментарии</li>
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
                        <div class="row">
                            <div class="col-md-3">
                                <div id="toolbarGames" class="p-10 m-t-25">
                                    <div class="form-group">
                                        <label for="user" class="form-label">Публикация</label>
                                        {!! Form::select('confirmed', [3 => 'Все', 0 => 'Не опубликован', 1 => 'Опубликован'], 0 , ['class' => 'form-control', 'id' => 'confirmed_select']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="m-10">
                                    <table role="grid" id="tableCommentsAll"
                                           class="table responsive_table dataTables_wrapper"
                                           {{--data-method="post"--}}
                                           data-toggle="table"
                                           data-url="{{ route('m.comments.all') }}"
                                           data-id-field="id"
                                           {{--data-toolbar="#toolbarGames"--}}
                                           data-query-params="queryParams"
                                           {{--data-search="true"--}}
                                           data-show-refresh="true"
                                           data-sort-name="created_at"
                                           data-sort-order="desc"
                                           data-mobile-responsive="true"
                                           data-reorderable-rows="true"
                                           data-use-row-attr-func="true"
                                           data-cookie="true"
                                           data-cookie-id-table="tableComments"
                                           data-side-pagination="server"
                                           data-pagination="true"
                                           data-page-list="[10, 25, 50, 100, ALL]"
                                           data-show-footer="false">
                                        <thead>
                                        <tr>
                                            <th data-field="text" data-sortable="false" data-formatter="editCommentFormatter">Комментарий</th>
                                            <th data-field="id" data-width="250" data-formatter="editFormatter">Действия</th>
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
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/sweetalert2.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/select2/css/select2.min.css') }}">
@endsection
@section('script')
    <script src="{{ asset('assets/js/bootstrap-table/dist/bootstrap-table.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/locale/bootstrap-table-ru-RU.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/cookie/bootstrap-table-cookie.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/reorder-rows/bootstrap-table-reorder-rows.min.js') }}"></script>
    <script src="{{ asset('assets/js/table_dnd/dist/jquery.tablednd.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/js/tree/jstree.min.js') }}"></script>
    <script type="application/javascript">
        function queryParams(params) {
            params.confirmed = $('select[name="confirmed"]').val();
            return params;
        }
        function editCommentFormatter(value, row) {
            return '<div><strong>Игра: </strong><span class="txt-gray">' + row.game + '</span>, <strong>Игрок: </strong><span class="txt-gray">' + row.name
                + '</span>, <strong>Дата: </strong><span class="txt-gray">' + row.created_at + '</span></div><div><p class="p-t-10 txt-info">' + value + '</p></div';
        }
        function editFormatter(value, row) {
            var edBut = '';
            @if(Auth::user()->isRoleAction('comments_edit'))
                edBut +=  '<button type="button" onclick="publicComment(' + value + ')"'
                    + value + '"  class="btn btn-success btn-xs m-r-5" type="button" data-original-title="Опубликовать" title="Опубликовать"><i class="icofont icofont-comment"></i></button>';
            @endif
            @if(Auth::user()->isRoleAction('comments_delete'))
            edBut += '<button class="btn btn-danger btn-xs sweet-delete m-r-5" onclick="deleteComment(' + value + ')" type="button" data-original-title="Удалить" title="Удалить"><i class="icofont icofont-delete-alt"></i></button>';
            @endif

                edBut += '<a href="/comment/edit/' + value + '" class="btn btn-success btn-xs m-r-5" type="button" data-original-title="Редактировать" title="Редактировать"><i class="icofont icofont-edit-alt"></i></a>';

            edBut +=  '<a target="_blank" href="{{ config('app.url') }}'
                + row.url_game + '/"  class="btn btn-info btn-xs m-r-5" type="button" data-original-title="Просмотреть" title="Просмотреть"><i class="icofont icofont-eye-alt"></i></a>';
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

        let cooliesConfirmed = Cookies.get('admin_comment_page.confirmed');
        if (typeof cooliesConfirmed != "undefined" && cooliesConfirmed < 3) {
            $('select[name="confirmed"]').val(cooliesConfirmed);
        }

        const $table = $('#tableCommentsAll');

        $('select[name="confirmed"]').on('change', function() {
            $table.bootstrapTable('refresh');
            Cookies.set('admin_comment_page.confirmed', this.value);
        });

        function publicComment(commentId) {
            $.ajax({
                type: "POST",
                url: '/comment/public/' + commentId,
                cache: false,
                success: function(data)
                {
                    if (data.success) {
                        $table.bootstrapTable('refresh');
                    }

                    $.notify({
                        message:data.message,
                    }, {
                        type: data.success ? 'success' : 'danger',
                        mouse_over:true,
                    });
                }
            });
        }

        function deleteComment(commentId) {
            Swal.fire({
                title: 'Вы уверенны?',
                text: "Вы действительно хотите удалить этот комментарий?!",
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
                        url: '/comment/delete/' + commentId,
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
    </script>
@endsection