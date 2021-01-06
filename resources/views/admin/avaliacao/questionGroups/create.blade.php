<div id='widget_breadcrumb'>@include('admin.layouts.includes.breadcrumb')</div>

<div id='widget_title'>
    @IF (isset($action) && $action == 'show')
    Assunto
    @ELSE 
    @if (isset($questionGroup))
    Edição de Assunto
    @else 
    Cadastro de Assunto
    @endif
    @ENDIF
</div>
<div class="row" style="margin:10px;">
    <div class="col-lg-12">
        <form class="form-horizontal" name="form_questionGroup_create" id="form_questionGroup_create">
            {{ csrf_field() }}
            @if (isset($questionGroup))
            <input type="hidden" id='route' value='questionGroup/update/{{ $questionGroup->id }}'>
            <input type="hidden" id='method' value='patch'>
            @else
            <input type="hidden" id='route' value='questionGroup/store/'>
            <input type="hidden" id='method' value='post'>
            @endif
            <div class="card-body">
                <div class="form-row">
                    <div class="col-md-3 mb-3">
                        <label>ID: </label>
                        @if (isset($questionGroup))
                        <input type="text" class="form-control" id="validationServer01" value="{{ str_pad( $questionGroup->id , 8, "0", STR_PAD_LEFT) }}"  disabled="disabled">
                        @else
                        <input type="text" class="form-control" id="validationServer01" value="########"  disabled="disabled">
                        @endif
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 mb-3">
                        <label>Assunto:</label>
                        <input type="text" id="name" name="name" class="form-control" style="" value="{{ (isset($questionGroup)) ? $questionGroup->name : '' }}">
                    </div>
                </div>
            </div>

            <div class="form-actions" style='margin-top:15px;'>
                @IF (!isset($action) || $action != 'show')
                <input type="button" value="Salvar" class="btn btn-success btn-save-form">
                @ENDIF
                <input type="button" value="Voltar" class="btn btn-success"  onclick="openRoute('avaliacao/questionGroup');">
            </div>
        </form>
    </div>
</div>

<div id="addAnswer" style=""></div>


<script src="{{asset('js/avaliacao/questionGroups.js')}}"></script> 
@IF (isset($action) && $action == 'show')
<script>
    $(document).ready(function () {
        $('input:not([type=button])').attr('disabled', 'disabled');
        $('select').attr('disabled', 'disabled');
    });
</script>
@ENDIF