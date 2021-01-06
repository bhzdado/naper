<div id='widget_breadcrumb'>@include('admin.layouts.includes.breadcrumb')</div>

<div id='widget_title'>
    @IF (isset($action) && $action == 'show')
    Empresas
    @ELSE 
    @if (isset($role))
    Edição de Empresa
    @else 
    Cadastro de Empresa
    @endif
    @ENDIF
</div>
<div class="row" style="margin:10px;">
    <div class="col-lg-12">
        <form class="form-horizontal" name="form_company_create" id="form_company_create">
            {{ csrf_field() }}
            @if (isset($company))
            <input type="hidden" id='route' value='company/update/{{ $company->id }}'>
            <input type="hidden" id='method' value='patch'>
            @else
            <input type="hidden" id='route' value='company/store/'>
            <input type="hidden" id='method' value='post'>
            @endif
            <div class="card-body">
                <div class="form-row">
                    <div class="col-md-6 mb-3">
                        <label class="">ID: </label>
                        <span>@if (isset($company)) {{ str_pad( $company->id , 8, "0", STR_PAD_LEFT) }} @else ######## @endif</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="">Ativo*: </label>
                        @IF(isset($companyAuth) && ($companyAuth->hasRole('administrador') || $companyAuth->hasRole('gerente')))
                        <select id='active' name="active">
                            <option value="0" {{ ((isset($company)) ? ($company->active == 0) ? "selected" : "" : "") }}>Não</option>
                            <option value="1" {{ ((isset($company)) ? ($company->active == 1) ? "selected" : "" : "") }}>Sim</option>
                        </select>
                        @ELSE
                        <span>{{ (isset($companyAuth) && $companyAuth->active == 0) ? "Não" : "Sim" }}</span>
                        <input type="hidden" name="active" id="active" value="{{ (isset($company)) ? $company->active : '' }}">
                        @ENDIF
                    </div>     
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label class="">Razão Social*: </label>
                    <input type="text" name="company_name" id="company_name" class="form-control" style="" value="{{ (isset($company)) ? $company->company_name : '' }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="">Nome Fantasia: </label>
                    <input type="text" name="fantasy_name" id="fantasy_name" class="form-control" style="" value="{{ (isset($company)) ? $company->fantasy_name : '' }}">
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-4 mb-3">
                    <label class="">CNPJ*: </label>
                    <input type="text" name="cnpj" id="cnpj" class="form-control mask-cnpj" value="{{ (isset($company)) ? $company->cnpj : '' }}">
                </div>
                <div class="col-md-8 mb-3">
                    <label class="">E-mail*: </label>
                    <input type="text" name="email" id="email" class="form-control" style="" value="{{ (isset($company)) ? $company->email : '' }}">
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label class="">Responsável*: </label>
                    <input type="text" name="responsible" id="responsible" class="form-control" value="{{ (isset($company)) ? $company->responsible : '' }}" style=''>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label class="">Cep: </label>
                    <input type="text" name="cep" id="cep" class="form-control mask-cep" value="{{ (isset($company)) ? $company->cep : '' }}" style=''>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label class="">Endereço: </label>
                    <input type="text" name="address" id="address" class="form-control" value="{{ (isset($company)) ? $company->address : '' }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="">Número: </label>
                    <input type="text" name="number" id="number" class="form-control" value="{{ (isset($company)) ? $company->number : '' }}" style=''>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="">Complemento: </label>
                    <input type="text" name="complement" id="complement" class="form-control" value="{{ (isset($company)) ? $company->complement : '' }}">
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-4 mb-3">
                    <label class="">Bairro: </label>
                    <input type="text" name="neighborhood" id="neighborhood" class="form-control" value="{{ (isset($company)) ? $company->neighborhood : '' }}" style=''>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="">Cidade: </label>
                    <input type="hidden" name="city_id" id="city_id" class="form-control" value="{{ (isset($company)) ? $company->city_id : '' }}">
                    <input type="text" name="city_name" id="city_name" class="form-control" value="{{ (isset($company)) ? (((isset($company->city)) ? $company->city->name : '')) : '' }}" disabled="disabled">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="">Estado: </label>
                    <input type="hidden" name="state_id" id="state_id" class="form-control" value="{{ (isset($company)) ? $company->state_id : '' }}">
                    <input type="text" name="state_name" id="state_name" class="form-control" value="{{ (isset($company)) ? (((isset($company->state)) ? $company->state->name : '')) : '' }}" disabled="disabled">
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-4 mb-3">
                    <label class="">Telefone: </label>
                    <input type="text" name="telephone" id="telephone" class="form-control mask-telephone" value="{{ (isset($company)) ? $company->telephone : '' }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="">Insc. Est.: </label>
                    <input type="text" name="state_registration" id="state_registration" class="form-control" style="" value="{{ (isset($company)) ? $company->state_registration : '' }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="">Insc. Mun.: </label>
                    <input type="text" name="municipal_registration" id="municipal_registration" class="form-control" style="" value="{{ (isset($company)) ? $company->municipal_registration : '' }}">
                </div>
            </div>
            <div class="form-actions">
                 @IF (!isset($action) || $action != 'show')
                <input type="button" value="Salvar" class="btn btn-success btn-save-form">
                @ENDIF
                <input type="button" value="Voltar" class="btn btn-success"  onclick="openRoute('company');">
            </div>
        </form>
    </div>
</div>

<script src="{{asset('js/company/script.js')}}"></script> 
@IF (isset($action) && $action == 'show')
    <script>
        $('input[type=text]').attr('disabled', 'disabled');
        $('select').attr('disabled', 'disabled');
        
    </script>
@ENDIF