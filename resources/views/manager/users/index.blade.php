@extends('manager.app')
@section('title', 'Все пользователи')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3><i class="icofont icofont-users-social"></i> Все пользователи</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item active">Все пользователи</li>
                    </ol>
                </div>
                <div class="col-lg-6">
                    <div class="bookmark pull-right">
                        <ul>
                            <li><a href="{{ route('m.users.new') }}" data-container="body" data-toggle="popover" data-placement="top" title="" data-original-title="Добавить нового пользователя"><i data-feather="user-plus"></i></a></li>
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
                    <div class="card-content">
                        <div class="row"></div>
                        <div class="m-10">
                            <table role="grid" id="tablePages"
                                   class="table responsive_table dataTables_wrapper"
                                   {{--data-method="post"--}}
                                   data-toggle="table"
                                   data-url="{{ route('m.users.post.all') }}"
                                   data-id-field="id"
                                   data-search="true"
                                   data-show-refresh="true"
                                   {{--data-parent-idfield="parent_id"
                                   data-root-parent-id="0"--}}
                                   data-tree-show-field="title"
                                   data-sort-name="id"
                                   data-sort-order="desc"
                                   data-mobile-responsive="true"
                                   data-reorderable-rows="true"
                                   data-use-row-attr-func="true"
                                   data-cookie="true"
                                   data-cookie-id-table="tablePages"
                                   data-minimum-count-columns="2"
                                   data-pagination="true"
                                   data-page-list="[10, 25, 50, 100, ALL]"
                                   data-show-footer="false">
                                <thead>
                                <tr>
                                    <th data-field="username" data-sortable="true" data-formatter="usernameFormatter">Ник</th>
                                    <th data-field="name" data-sortable="false">Полное имя</th>
                                    <th data-field="role" data-formatter="roleFormatter" data-sortable="false">Роль</th>
                                    <th data-field="status" data-width="100" data-formatter="statusFormatter" data-align="center">Статус</th>
                                    @if(Auth::user()->isRoleAction('users_edit') or Auth::user()->isRoleAction('users_delete'))
                                        <th data-field="id" data-width="120" data-formatter="editFormatter">Действия</th>
                                    @endif
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
    <script src="{{ asset('assets/js/table_dnd/dist/jquery.tablednd.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert2.js') }}"></script>
    <script type="application/javascript">
        function roleFormatter(value, row) {
            if (value == 1) {
                return '<span class="badge badge-pill pill-badge-success">Админ</span>';
            } else if (value == 2) {
                return '<span class="badge badge-pill pill-badge-warning">Менеджер</span>';
            }

            return '<span class="badge badge-pill pill-badge-primary">Веб</span>';
        }
        function statusFormatter(value, row) {
            if (value == 1) {
                return '<span class="badge flat-badge-success">активный</span>';
            }

            return '<span class="badge flat-badge-secondary">не активный</span>';
        }
        function usernameFormatter(value, row) {
            return '<div class="d-inline-block align-middle"><img class="img-radius img-30 align-top m-r-15 rounded-circle" src="' + row.avatar + '" alt="" data-original-title="" title="">\n' +
                '                                <div class="d-inline-block">\n' +
                '                                  <h6 class="f-w-600">' + value + '</h6>\n' +
                '                                </div>\n' +
                '                              </div>';
        }
        @if(Auth::user()->isRoleAction('users_edit') or Auth::user()->isRoleAction('users_delete'))

        var roleUser = "{{ Auth::user()->role }}";
        function editFormatter(value, row) {
            button = '';

            @if(Auth::user()->isRoleAction('users_edit'))
                    button += '<a href="/user/edit/' + value + '" class="btn btn-success btn-xs m-r-5" type="button" data-original-title="Редактировать" title="Редактировать"><i class="icofont icofont-edit-alt"></i></a>';
            @endif

            @if(Auth::user()->isRoleAction('users_delete'))

                if (roleUser == '3' && row.role == 1) {
                button += '';
            } else {
                button += '<button class="btn btn-danger btn-xs sweet-delete" onclick="deleteUser(' + value + ')" type="button" data-original-title="Удалить" title="Удалить"><i class="icofont icofont-delete-alt"></i></button>';
            }
            @endif

            return button;
        }
        @endif

        var $table = $('#tablePages');

        /*$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });*/

        function deleteUser(userId) {

            Swal.fire({
                title: 'Вы уверенны?',
                text: "Вы действительно хотите удалить этого пользователя?!",
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
                        url: '/apiadmin/users/delete/' + userId,
                        cache: false,
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function (res) {

                            if (res.result) {
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

                            //Swal.close();

                        }
                    });


                }
            })
        }

    </script>
@endsection