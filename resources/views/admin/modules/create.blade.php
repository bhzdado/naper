<div id='widget_breadcrumb'>@include('admin.layouts.includes.breadcrumb')</div>

<div id='widget_title'>
    @IF (isset($action) && $action == 'show')
    Módulo
    @ELSE 
    @if (isset($module))
    Edição de Módulo
    @else 
    Cadastro de Módulo
    @endif
    @ENDIF
</div>
<div class="row" style="margin:10px;">
    <div class="col-lg-12">
        <form class="form-horizontal" name="form_module_create" id="form_module_create">
            {{ csrf_field() }}
            @if (isset($module))
            <input type="hidden" id='route' value='module/update/{{ $module->id }}'>
            <input type="hidden" id='method' value='patch'>
            @else
            <input type="hidden" id='route' value='module/store/'>
            <input type="hidden" id='method' value='post'>
            @endif
            <div class="card-body">
                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label>ID: </label>
                        @if (isset($module))
                        <input type="text" class="form-control" id="validationServer01" value="{{ str_pad( $module->id , 8, "0", STR_PAD_LEFT) }}"  disabled="disabled">
                        @else
                        <input type="text" class="form-control" id="validationServer01" value="########"  disabled="disabled">
                        @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Publicardo?</label>
                        <select class="form-control" name="published" id="published"  style="">
                            <option value="0" {{ (isset($module->published) && $module->published == 0) ? "selected:selected" : "" }} > Não </option>
                            <option value='1' {{ (isset($module->published) && $module->published == 1) ? "selected:selected" : "" }} > Sim </option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label>Imposto:</label>
                        <input type="text" id="tribute" mode="tribute" class="form-control autocomplete" style="" value="{{ (isset($module->tribute)) ? $module->tribute->tribute_name : '' }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label>Módulo:</label>
                        <input type="text" name="name" id="name" class="form-control" style="" value="{{ (isset($module)) ? $module->name : '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Subtitulo:</label>
                        <input type="text" name="name" id="label" class="form-control" style="" value="{{ (isset($module)) ? $module->label : '' }}">
                    </div>
                </div>
            </div>

            <div class="form-actions" style='margin-top:15px;'>
                @IF (!isset($action) || $action != 'show')
                <input type="button" value="Salvar" class="btn btn-success btn-save-form">
                @ENDIF
                <input type="button" value="Voltar" class="btn btn-success"  onclick="openRoute('module');">
            </div>
        </form>
    </div>
</div>

<script src="{{asset('js/module/script.js')}}"></script> 
@IF (isset($action) && $action == 'show')
<script>
                $('input[type=text]').attr('disabled', 'disabled');
                $('select').attr('disabled', 'disabled');

</script>
@ENDIF