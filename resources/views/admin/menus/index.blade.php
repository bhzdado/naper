<link rel="stylesheet" href="{{asset('back-assets/css/menu-editor/bootstrap.min.css')}}" />
<link rel="stylesheet" href="{{asset('back-assets/css/menu-editor/all.css')}}" />
<link rel="stylesheet" href="{{asset('back-assets/css/menu-editor/bootstrap-iconpicker.min.css')}}" />

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<style>
    #myEditor > li {
        border: 1px solid rgba(0,0,0,.125)!important;
    }

    #dialog-add {
        position: absolute;
        z-index: 999999;
        background: #FFFFFF;
        border: 1px solid black;
        width: 90%;
        -moz-border-radius:10px;
        -webkit-border-radius:10px;
        border-radius:10px;
    }

    #dialog-add-title {
        background: #1E61A0;
        padding: 10px;
        margin-bottom: 20px;
        font-weight: bold;
        color: #FFFFFF;
        font-size: 13px;
    }
</style>

<div id='widget_breadcrumb'>@include('admin.layouts.includes.breadcrumb')</div>

<div id='widget_title'>
    {{ $title }}
</div>


<div class="row">
    <div class="card-body" id="dialog-add" >
        <div id="dialog-add-title">Adicionar/Editar item de Menu</div>
        <form id="frmEdit" class="form-horizontal">
            <div class="form-group">
                <label for="text">Nome</label>
                <div class="input-group">
                    <input type="text" class="form-control item-menu" name="text" id="text" placeholder="Nome">
                    <div class="input-group-append">
                        <button type="button" id="myEditor_icon" class="btn btn-outline-secondary"></button>
                    </div>
                </div>
                <input type="hidden" name="icon" class="item-menu">
            </div>
            <div class="form-group">
                <label for="href">URL</label>
                <input type="text" class="form-control item-menu" id="href" name="href" placeholder="URL">
            </div>
            <div class="form-group">
                <label for="target">Target</label>
                <select name="target" id="target" class="form-control item-menu">
                    <option value="_self">Self</option>
                    <option value="_blank">Blank</option>
                    <option value="_top">Top</option>
                </select>
            </div>
            <div class="form-group">
                <label for="title">Legenda</label>
                <input type="text" name="title" class="form-control item-menu" id="title" placeholder="Legenda">
            </div>
            <div class="form-group">
                <label for="title">Publicar?</label>
                <select name="published" id="published" class="form-control item-menu">
                    <option value="0">Não</option>
                    <option value="1">Sim</option>
                </select>
            </div>
        </form>
        <div class="card-footer">
            <button type="button" id="btnUpdate" class="btn btn-primary" disabled><i class="fas fa-sync-alt"></i> Alterar</button>
            <button type="button" id="btnAdd" class="btn btn-success"><i class="fas fa-plus"></i> Adicionar</button>
            <button type="button" id="btnCancel" class="btn btn-success"><i class="fas fa-close"></i> Cancelar</button>
        </div>
    </div>
    <div class="col-md-12">

        <div class="widget-content nopadding" style='padding-bottom: 0px;'>
            <div style="padding:10px; padding-bottom: 0px;">
                <div class='grid-filters-button' style='padding-bottom: 0px; text-align: right;'>
                    @IF($permissions['create'] == true)
                    <button onclick="add();" type="button" class="mb-1 btn  btn-sm btn-primary">
                        <i class=" mdi mdi-plus-box mr-1"></i> Adicionar
                    </button>
                    @ENDIF
                </div>
            </div>
        </div>
        <div class="card-body">
            <ul id="myEditor" class="sortableLists list-group">
            </ul>
        </div>
        @IF($permissions['create'] == true)
         <button id="btnOutput" type="button" class="btn btn-success"><i class="fas fa-check-square"></i> Salvar Alterações</button>
        @ENDIF
    </div>

</div>
<script src="{{asset('back-assets/js/menu-editor/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('back-assets/js/menu-editor/jquery-menu-editor.js')}}"></script>
<script src="{{asset('back-assets/js/menu-editor/iconset/fontawesome5-3-1.js')}}"></script>
<script src="{{asset('back-assets/js/menu-editor/bootstrap-iconpicker.js')}}"></script>
<script src="{{asset('js/menu/index.js')}}"></script>
