<div id='widget_breadcrumb'>@include('admin.layouts.includes.breadcrumb')</div>

<div id='widget_title'>
        @IF (isset($action) && $action == 'show')
        Permissões
    @ELSE 
        @if (isset($permission))
        Edição de Permissão
        @else 
        Cadastro de Permissão
        @endif
    @ENDIF
</div>
<div class="row" style="margin:10px;">
    <div class="col-lg-12">
        <form class="form-horizontal" name="form_permission_create" id="form_permission_create">
            {{ csrf_field() }}
            @if (isset($permission))
            <input type="hidden" id='route' value='permission/update/{{ $permission->id }}'>
            <input type="hidden" id='method' value='patch'>
            @else
            <input type="hidden" id='route' value='permission/store/'>
            <input type="hidden" id='method' value='post'>
            @endif
            <div class="card-body">
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label>ID: </label>
                        @if (isset($permission))
                        <input type="text" class="form-control" id="validationServer01" value="{{ str_pad( $permission->id , 8, "0", STR_PAD_LEFT) }}"  disabled="disabled">
                        @else
                        <input type="text" class="form-control" id="validationServer01" value="########"  disabled="disabled">
                        @endif
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label>Perfil:</label>
                        <input type="text" name="slug" id="slug" class="form-control" style="" value="{{ (isset($permission)) ? $permission->slug : '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Descrição</label>
                        <input type="text" name="name" id="name" class="form-control" style="" value="{{ (isset($permission)) ? $permission->name : '' }}">
                    </div>
                </div>
            </div>

            <div class="form-actions" style='margin-top:15px;'>
                @IF (!isset($action) || $action != 'show')
                <input type="button" value="Salvar" class="btn btn-success btn-save-form">
                @ENDIF
                <input type="button" value="Voltar" class="btn btn-success"  onclick="openRoute('permission');">
            </div>
        </form>
    </div>
</div>

<script src="{{asset('js/permission/script.js')}}"></script> 
@IF (isset($action) && $action == 'show')
    <script>
        $('input[type=text]').attr('disabled', 'disabled');
        $('select').attr('disabled', 'disabled');
        
    </script>
@ENDIF