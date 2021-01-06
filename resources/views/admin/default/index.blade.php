<link rel="stylesheet" href="{{asset('back-assets/plugins/data-tables/jquery.datatables.min.css')}}" />
<link rel="stylesheet" href="{{asset('back-assets/plugins/data-tables/datatables.bootstrap4.min.css')}}" />
<link rel="stylesheet" href="{{asset('back-assets/plugins/data-tables/responsive.datatables.min.css')}}" />
<link rel="stylesheet" href="{{asset('back-assets/plugins/data-tables/buttons.dataTables.min.css')}}" />

<div id='widget_breadcrumb'>@include('admin.layouts.includes.breadcrumb')</div>

<div id='widget_title'>{{ $title }}</div>

<div class="row">
    <div class="col-lg-12">
        <div class="widget-content nopadding" style='padding-bottom: 0px;'>
            <div style="padding:10px; padding-bottom: 0px;">
                <div class='grid-filters-button' style='padding-bottom: 0px; text-align: right;'>
                    @IF($permissions['create'] == true)
                    <button onclick="openRoute('{{ $route }}/create');" type="button" class="mb-1 btn  btn-sm btn-primary">
                        <i class=" mdi mdi-plus-box mr-1"></i> Adicionar
                    </button>
                    @ENDIF
                </div>
            </div>
        </div>

        <div class="card card-default" style="    margin: 15px; padding-top: 10px;">
            <div class="card-body">
                <form id='frm_search'>
                    @foreach ($data['filters'] as $filter)
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="{{ $filter['name'] }}">{{ $filter['title'] }}</label>
                            @switch($filter['type'])
                                @case('select')
                                <select class="form-control" name="{{ $filter['name'] }}" id="{{ $filter['name'] }}"  style="{{ $filter['style'] }}">
                                    <option value=''> Selecione </option>
                                    @foreach ($filter['options'] as $idOption => $option)
                                        <option value='{{ $idOption }}'> {{ $option }} </option>
                                    @endforeach
                                </select>
                                    @break
                                 @default
                                    <input type="{{ $filter['type'] }}" class="form-control" name="{{ $filter['name'] }}" id="{{ $filter['name'] }}" style="{{ $filter['style'] }}"/>
                                    @break
                            @endswitch
                        </div>
                    </div>
                    @endforeach
                    <button class="btn btn-primary" id="btn_search" type="button" style="margin: 10px;">Buscar</button>
                    <button class="btn btn-primary" id="btn_clear" type="button" style="margin: 10px;">Limpar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<a href="#" id="foo"></a>
<table id="jsGrid" class="display table dt-responsive nowrap table-striped table-bordered" style="width:92%; margin:10px; margin-bottom: 30px;">
    <thead>
        <tr>
            @foreach ($data['fields'] as $field)
            <th>{{ $field['title'] }}</th>
            @endforeach
        </tr>
    </thead>
</table>
<br><br>

<script src="{{asset('back-assets/plugins/data-tables/jquery.datatables.min.js')}}"></script>
<script src="{{asset('back-assets/plugins/data-tables/datatables.bootstrap4.min.js')}}"></script>
<script src="{{asset('back-assets/plugins/data-tables/datatables.responsive.min.js')}}"></script>

<script src="{{asset('back-assets/plugins/data-tables/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('back-assets/plugins/data-tables/pdfmake.min.js')}}"></script>
<script src="{{asset('back-assets/plugins/data-tables/vfs_fonts.js')}}"></script>
<script src="{{asset('back-assets/plugins/data-tables/buttons.html5.min.js')}}"></script>

<script>
                        $(document).ready(function () {
                        var search = false;
                        var accessToken = "Bearer " + $.cookie(APP_NAME + '-Token');
                        
                        var table = $('#jsGrid').DataTable({
                        "processing": true,
                                "serverSide": true,
                                language: {
                                url: URL + 'lang/dataTable/Portuguese-Brasil'
                                },
                                "aLengthMenu": [[2, 20, 30, 50, 75, - 1], [2, 20, 30, 50, 75, 100]],
                                "pageLength": 20,
                                "bFilter": false,
                                "dom": '<"row justify-content-between top-information"lf>rt<"row justify-content-between bottom-information"ip><"clear">',
                                "columns": [
                                        @foreach ($data['fields'] as $field)
                                { "data": "{{ $field['name'] }}" },
                                        @endforeach
                                ],
                                buttons: [
                                        'copy', 'excel', 'pdf'
                                ],
                                "ajax": {
                                url: API_URL + "{{ $route }}/loadDataGrid",
                                        type: "GET",
                                        data: {
                                        filters: function(d) { return $('#frm_search').serialize() },
                                        },
                                        headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                                'Authorization': accessToken
                                        },
                                        "dataSrc": function (json) {
                                            if(json.success === false){
                                                $.MessageBox({
                                                buttonDone: "OK",
                                                buttonFail: "Cancel",
                                                message: "A sua autenticação expirou. Por favor autentique-se novamente.",
                                                input: {
                                                    email: {
                                                        type: "text",
                                                        label: "E-mail:",
                                                    },
                                                    password: {
                                                        type: "password",
                                                        label: "Senha:",
                                                    }
                                                },
                                                filterDone: function (data) {
                                                    apiClient.post('login', data, function (response) {
                                                        $.cookie(APP_NAME + '-Token', response.data.accessToken);
                                                        $.cookie(APP_NAME + '-UserId', response.data.user.id);

                                                        loadRoute($.cookie(APP_NAME + '-ra'));
                                                    });
                                                }
                                            });
                                            }
                                            $('#jsGrid_info').css('width', '100%').css('text-align', 'right');
                                            $('#jsGrid_paginate').css('width', '100%');
                                            if (search == true){
                                                window.location.href = '#';
                                            }
                                            search = false;
                                            return json.data;
                                        }
                                }

                        });
                        //jsGrid_info

                        $('#btn_search').click(function(){
                        search = true;
                        table.ajax.reload(null, true);
                        });
                        $('#btn_clear').click(function(){
                        $.each($('#frm_search input'), function(){
                        $(this).val('');
                        });
                        });
                        });
</script>