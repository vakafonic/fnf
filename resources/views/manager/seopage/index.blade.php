@extends('manager.app')
@section('title', 'СЕО страницы')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3><i class="icofont icofont-newspaper"></i> СЕО страницы</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item active">СЕО страницы</li>
                    </ol>
                </div>
                <div class="col-lg-6">
                    <div class="bookmark pull-right">
                        <ul>
                            <li><a href="{{ route('m.seopage.new') }}" data-container="body" data-toggle="popover" data-placement="top" title="" data-original-title="Добавить новую страницу"><i data-feather="file-plus"></i></a></li>
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
                        <h5 class="m-b-0">СЕО страницы</h5>
                    </div>
                    <div class="card-content">
                        <div class="row"></div>
                        <div class="m-10">
                            <div id="toolbarSeo">
                                <a href="{{ route('m.seopage.new') }}" class="btn btn-primary "><span class="icon-plus"></span> Новая СЕО страница</a>
                            </div>
                            <table role="grid" id="tableSeoAll"
                                   class="table responsive_table dataTables_wrapper"
                                   data-toggle="table"
                                   data-url="{{ route('m.seopage.all') }}"
                                   data-id-field="id"
                                   data-toolbar="#toolbarSeo"
                                   data-search="true"
                                   data-show-refresh="true"
                                   data-sort-name="sort"
                                   data-sort-order="asc"
                                   data-mobile-responsive="true"
                                   data-reorderable-rows="true"
                                   data-use-row-attr-func="true"
                                   data-cookie="true"
                                   data-cookie-id-table="tableSeo"
                                   data-minimum-count-columns="2"
                                   data-side-pagination="server"
                                   data-pagination="true"
                                   data-page-list="[10, 25, 50, 100, ALL]"
                                   data-show-footer="false">
                                <thead>
                                <tr>
                                    <th data-field="value">Название</th>
                                    <th data-field="comment">Комментарий</th>
                                    <th data-field="public" data-align="center" data-width="120" data-formatter="publicFormatter">Опубликован</th>
                                    <th data-field="sort" data-align="center" data-width="120" data-align="center" data-sortable="true">Сортировки</th>
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
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/reorder-rows/bootstrap-table-reorder-rows.min.js') }}"></script>
    <script src="{{ asset('assets/js/table_dnd/dist/jquery.tablednd.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert2.js') }}"></script>
    <script type="application/javascript">
        function editFormatter(value, row) {
            dBut = '<a href="/seo-page/edit/' + value + '" class="btn btn-success btn-xs m-r-5" ' +
                'data-original-title="Редактировать" title="Редактировать"><i class="icofont icofont-edit-alt"></i></a>';
            dBut += '<button class="btn btn-danger btn-xs sweet-delete m-r-5" onclick="deleteSeopage(' + value + ')" type="button" data-original-title="Удалить" title="Удалить"><i class="icofont icofont-delete-alt"></i></button>';
            dBut +=  '<a target="_blank" href="{{ config('app.url') }}'
                + row.url + '"  class="btn btn-info btn-xs m-r-5" type="button" data-original-title="Просмотреть" title="Просмотреть"><i class="icofont icofont-eye-alt"></i></a>';
            return dBut;
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

        var $table = $('#tableSeoAll');

        $table.on('reorder-row.bs.table', function (e, order) {
            var dataSend = [];
            $.each(order, function (i, value) {
                dataSend.push(value.id);
            });

            $.post( "{{ route('m.seopage.sort') }}", { data: dataSend })
                .done(function( data ) {
                    $table.bootstrapTable('refresh');
                });
        });

        $table.on('load-success.bs.table', function (e, order) {

            $('[data-toggle="tooltip"]').tooltip();

            $('input[name="check_public"]').change(function() {
                $.ajax({
                    type: "POST",
                    url: '/seo-page/public/' + $(this).val() + '/' + (this.checked ? 1 : 0),
                    cache: false,
                    success: function(data)
                    {
                        if (data.success) {
                            //$table.bootstrapTable('refresh');
                        }
                    }
                });
            });
        });

        function deleteSeopage(pageId) {
            Swal.fire({
                title: 'Вы уверенны?',
                text: "Вы действительно хотите удалить эту страницу?!",
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
                        url: '/seo-page/delete/' + pageId,
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