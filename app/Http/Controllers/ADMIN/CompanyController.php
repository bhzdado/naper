<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Company;

class CompanyController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $user = $this->getAuthUser($request);

        $permissions = array('create' => false);
        if ($user->hasRole(['administrador', 'gerente'])) {
            $permissions = array('create' => true);
        }

        return view("admin/default/index", array(
            'title' => 'Empresas',
            'route' => 'company',
            'userAuth' => $user,
            'permissions' => $permissions,
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $user = $this->getAuthUser($request);
        if ($user->hasRole(['administrador', 'gerente'])) {
            return view("admin/companies/create", array(
                'title' => 'Empresas',
                'userAuth' => $user,
            ));
        }

        return view("admin/errors/access_denied", array(
            'userAuth' => $user,
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $user = $this->getAuthUser($request);
        if ($user->hasRole(['administrador', 'gerente'])) {
            return view('admin/companies/create', array(
                'action' => 'show',
                'title' => 'Empresas',
                'company' => Company::select("companies.*")
                        ->orderby('companies.company_name')
                        ->where('companies.id', $id)
                        ->first()
            ));
        }

        return view("admin/errors/access_denied", array(
            'userAuth' => $user,
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        $user = $this->getAuthUser($request);
        if ($user->hasRole(['administrador', 'gerente'])) {
            return view('admin/companies/create', array(
                'title' => 'Empresas',
                'userAuth' => $user,
                'company' => Company::select("companies.*")
                        ->orderby('companies.company_name')
                        ->where('companies.id', $id)
                        ->first()
            ));
        }

        return view("admin/errors/access_denied", array(
            'userAuth' => $user,
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        if (!$id) {
            return ['status' => false, 'data' => [], 'message' => "Nenhum ID informado.", 'errors' => 'Empresa não removida.'];
        }

        $user = $this->getAuthUser($request);
        if ($user->hasRole(['administrador', 'gerente'])) {
            $company = Company::where('id', $id)->first();
            $company->delete();

            return $this->sendResponse($company, "Empresa removida com sucesso.", "Grid::company");
        }

        return view("admin/errors/access_denied", array(
            'userAuth' => $user,
        ));
    }

    public function openGrid(Request $request) {
        $user = $this->getAuthUser($request);
        $permissions = array('create' => false, 'edit' => false, 'delete' => false, 'show' => true);
        if ($user->hasRole(['administrador', 'gerente'])) {
            $permissions = array('create' => true, 'edit' => true, 'delete' => true, 'show' => true);
        }

        $data = array(
            "fields" => array(
                array('type' => 'text', 'name' => 'idRegister', 'title' => 'Código', 'width' => '10%', 'align' => "center"),
                array('type' => 'text', 'name' => 'fantasy_name', 'title' => 'Nome Fantasia', 'width' => '40%'),
                array('type' => 'text', 'name' => 'company_name', 'title' => 'Razão Social', 'width' => '40%'),
                array('type' => 'text', 'name' => 'responsible', 'title' => 'Responsável', 'width' => '30%'),
                array('type' => 'text', 'name' => 'cnpj', 'title' => 'CNPJ', 'width' => '25%'),
                array('type' => 'text', 'name' => 'active', 'title' => 'Ativo', 'width' => '5%'),
                array('type' => 'text', 'name' => 'action', 'title' => '', 'width' => '5%'),
            ),
            'filters' => array(
                array('type' => 'text', 'name' => 'companies.fantasy_name', 'title' => 'Nome Fantasia', 'style' => 'width:90%', "value" => (isset($conditions['companies.fantasy_name'])) ? $conditions['companies.fantasy_name'] : ''),
                array('type' => 'text', 'name' => 'companies.company_name', 'title' => 'Razão Social', 'style' => 'width:90%', "value" => (isset($conditions['companies.company_name'])) ? $conditions['companies.company_name'] : ''),
                array('type' => 'text', 'name' => 'companies.cnpj', 'title' => 'CNPJ', 'class' => "form-control mask-cnpj", 'style' => 'width:60%', "value" => (isset($conditions['companies.cnpj'])) ? $conditions['companies.cnpj'] : ''),
            ),
            'permissions' => $permissions,
        );

        return view('admin/default/index', array(
            'title' => 'Empresas',
            'route' => 'company',
            'userAuth' => $user,
            'permissions' => $permissions,
            'data' => $data,
        ));
    }

}
