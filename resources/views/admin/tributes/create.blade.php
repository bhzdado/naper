<div id='widget_breadcrumb'>@include('admin.layouts.includes.breadcrumb')</div>

<div id='widget_title'>
    @IF (isset($action) && $action == 'show')
    Imposto
    @ELSE 
    @if (isset($tribute))
    Edição de Imposto
    @else 
    Cadastro de Imposto
    @endif
    @ENDIF
</div>
<div class="row" style="margin:10px;">
    <div class="col-lg-12">
        <form class="form-horizontal" name="form_tribute_create" id="form_tribute_create">
            {{ csrf_field() }}
            @if (isset($tribute))
            <input type="hidden" id='route' value='tribute/update/{{ $tribute->id }}'>
            <input type="hidden" id='method' value='patch'>
            @else
            <input type="hidden" id='route' value='tribute/store/'>
            <input type="hidden" id='method' value='post'>
            @endif
            <div class="card-body">
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label>ID: </label>
                        @if (isset($tribute))
                        <input type="text" class="form-control" id="validationServer01" value="{{ str_pad( $tribute->id , 8, "0", STR_PAD_LEFT) }}"  disabled="disabled">
                        @else
                        <input type="text" class="form-control" id="validationServer01" value="########"  disabled="disabled">
                        @endif
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label>Imposto:</label>
                        <input type="text" name="name" id="name" class="form-control" style="" value="{{ (isset($tribute)) ? $tribute->name : '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Publicardo?</label>
                        <select class="form-control" name="published" id="published"  style="">
                            <option value="0" {{ (isset($tribute->published) && $tribute->published == 0) ? "selected:selected" : "" }} > Não </option>
                            <option value='1' {{ (isset($tribute->published) && $tribute->published == 1) ? "selected:selected" : "" }} > Sim </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-actions" style='margin-top:15px;'>
                @IF (!isset($action) || $action != 'show')
                <input type="button" value="Salvar" class="btn btn-success btn-save-form">
                @ENDIF
                <input type="button" value="Voltar" class="btn btn-success"  onclick="openRoute('tribute');">
            </div>
        </form>
    </div>
</div>

<script src="{{asset('js/tribute/script.js')}}"></script> 
@IF (isset($action) && $action == 'show')
<script>
                $('input[type=text]').attr('disabled', 'disabled');
                $('select').attr('disabled', 'disabled');

</script>
@ENDIF