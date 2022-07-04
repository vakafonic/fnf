@extends('manager.app')
@section('title', 'Сообщения')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <h3><i class="icofont icofont-email"></i> Сообщения</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Главная</a></li>
                        <li class="breadcrumb-item active">Сообщения</li>
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
        <div class="email-wrap">
            <div class="row">
                <div class="col-xl-4 col-md-4 box-col-6 p-r-0">
                    <div class="email-right-aside">
                        <div class="card email-body">
                            <div class="pr-0 b-r-light">
                                <div class="email-top">
                                    <div class="row">
                                        <div class="col">
                                            <h5>Сообщения</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="inbox">
                                    <table role="grid" id="tableFeedback"
                                           class="table responsive_table dataTables_wrapper"
                                           data-toggle="table"
                                           data-url="{{ route('m.feedbacks.get') }}"
                                           data-search="false"
                                           data-show-refresh="false"
                                           data-sort-name="created_at"
                                           data-sort-order="desc"
                                           data-mobile-responsive="true"
                                           data-reorderable-rows="true"
                                           data-use-row-attr-func="true"
                                           data-cookie="true"
                                           data-cookie-id-table="tableFeedback"
                                           data-show-footer="false"
                                           data-minimum-count-columns="1"
                                           data-pagination="true"
                                           data-page-list="[10, 25, 50, 100, ALL]"
                                           data-show-header="false" >
                                        <thead>
                                        <tr>
                                            <th data-field="email" data-formatter="showMessage" data-sortable="false">Сообщение</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-md-8 box-col-6 p-l-0">
                    <div class="email-right-aside">
                        <div class="card email-body radius-left">
                            <div class="pl-0">
                                <div class="tab-content">
                                    <div class="tab-pane fade active show" id="pills-darkprofile" role="tabpanel" aria-labelledby="pills-darkprofile-tab">
                                        <div class="email-content">
                                            <div class="email-top">
                                                <div class="row">
                                                    <div class="col-md-6 xl-100 col-sm-12">
                                                        <div class="media">
                                                            <div class="media-body">
                                                                <h6><small class="emailFh6"></small></h6>
                                                            </div>
                                                        </div>
                                                        <p><a class="user-urlView" href="" target="_blank"></a> </p>
                                                    </div>
                                                    <div class="col-md-6 col-sm-12">
                                                        <div class="float-right d-flex">
                                                            <p class="user-emailid"></p><br>
                                                        </div>
                                                        <button class="btn btn-danger btn-xs sweet-delete" id="deleteMessageF" data-id="" onclick="deleteMessage(this)" type="button" data-original-title="Удалить" title="Удалить"><i class="icofont icofont-delete-alt"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="email-wrapper">
                                                <p><i class="icofont icofont-hand-left"></i> Нажмите на сообщение чтобы отобразить его здесь</p>
                                            </div>
                                        </div>
                                    </div>
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
    <style>
        #deleteMessageF {
            position: absolute;
            right: 10px;
            bottom: 0;
            display: none;
        }
    </style>
@endsection
@section('script')
    <script src="{{ asset('assets/js/bootstrap-table/dist/bootstrap-table.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/locale/bootstrap-table-ru-RU.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-table/dist/extensions/cookie/bootstrap-table-cookie.min.js') }}"></script>
    <script src="{{ asset('assets/js/table_dnd/dist/jquery.tablednd.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert2.js') }}"></script>
    <script type="application/javascript">
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

        var loaderTxt = '<div class="loader-box">\n' +
            '                        <div class="loader-20"></div>\n' +
            '                      </div>';
        var emailWrapper = $('.email-wrapper');

        function showMessage(value, row) {
            return '<div onclick="showMessageFeedback(' + row.id + ')" class="media media-f media-f-' + row.id + ' ' + (row.view != 1 ? ' noView' : '') + '">\n' +
                '                                        <div class="media-body">\n' +
                '                                            <h6>' + row.email + '  <small><span class="digits">(' + row.date + ')</span></small></h6>\n' +
                '                                            <p>' + row.text + '</p>\n' +
                '                                        </div>\n' +
                '                                    </div>';
        }
        function showMessageFeedback(idMessage) {
            emailWrapper.html(loaderTxt);
            $('.emailFh6').html('');
            $('.user-emailid').html('');
            //$('#deleteMessageF').data('id', '';
            //$('.user-urlView').attr('htref', '').text('');

            $.ajax({
                method: "POST",
                url: '/feedbacks/message/' + idMessage,
                cache: false,
                success: function (res) {
                    emailWrapper.html(res.message);
                    $('.emailFh6').html(res.date);
                    $('.user-urlView').attr('htref', res.url ?? '').text(res.url ?? '');
                    $('#deleteMessageF').attr('data-id', res.id).show();
                    $('.user-emailid').html('<p>' + res.email + '</p>');

                    $('.media-f-' + idMessage).removeClass('noView');

                    $('.feedbackcount').text(res.count_no_view);
                }
            });
        }
        function deleteMessage(thisBtn) {
            var r = confirm("Вы действительно хотите удалить это сообщение?!");
            var idMess = $(thisBtn).attr('data-id');
            if (r == true) {
                $.ajax({
                    method: "POST",
                    url: '/feedbacks/delete-message/' + idMess,
                    cache: false,
                    success: function (res) {
                        $('.email-wrapper').html('<p><i class="icofont icofont-hand-left"></i> Нажмите на сообщение чтобы отобразить его здесь</p>');
                        $('.emailFh6').html('');
                        $('.user-emailid').html('');
                        $('#deleteMessageF').hide();
                        $('.media-f-' + idMess).parents('tr').remove();

                        if(res.success) {
                            Swal.fire(
                                'Удаление!',
                                'Вы удалили это сообщение',
                                'success'
                            )
                        }

                    }
                });
            }
        }
    </script>
@endsection