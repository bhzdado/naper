<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Company as CompanyResource;
use Illuminate\Http\Request;
use App\Company;

class CompanyController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $user = $this->getAuthUser($request);
        if ($user->hasRole(['administrador', 'gerente'])) {
            $query = $request->query('query');

            $data = Company::select("companies.*");

            $data->orWhere('company_name', 'LIKE', '%' . $query . '%');
            $data = $data->distinct()->get();

            if (count($data)) {
                return CompanyResource::collection($data);
            }

            return json_encode(array("data" => array(array(
                        'id' => 0,
                        'value' => "Nenhuma informação encontrada.",
                        'data' => "Nenhuma informação encontrada.",
            ))));
        }

        return view("app/errors/access_denied", array(
            'user' => $user,
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $user = $this->getAuthUser($request);
        if ($user->hasRole(['administrador', 'gerente'])) {
            $error = $this->ValidatorMake($request, array(
                array(
                    'company_name' => 'required|max:255',
                    'cnpj' => 'required|max:19',
                    'responsible' => 'required|max:255',
                    'email' => 'required|email|max:255'),
                array(
                    'company_name' => 'Razão Social',
                    'cnpj' => 'CNPJ',
                    'responsible' => 'Responsável',
                    'email' => 'E-mail'
                )
            ));
            if ($error['error']) {
                return $this->sendError("Dados Incompletos.", $error['errors'], 401);
            }

            $input = $request->except(array('_token', 'active', 'companyId'));
            $input['cnpj'] = preg_replace('/[^0-9]/', '', $input['cnpj']);
            $input['cep'] = preg_replace('/[^0-9]/', '', $input['cep']);
            $input['telephone'] = preg_replace('/[^0-9]/', '', $input['telephone']);

            $company = Company::create($input);

            return $this->sendResponse($company, "Empresa cadastrada com sucesso.", "Grid::company");
        }

        return view("app/errors/access_denied", array(
            'user' => $user,
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $user = $this->getAuthUser($request);
        if ($user->hasRole(['administrador', 'gerente'])) {
            $error = $this->ValidatorMake($request, array(
                array(
                    'company_name' => 'required|max:255',
                    'cnpj' => 'required|max:19',
                    'responsible' => 'required|max:255',
                    'email' => 'required|email|max:255'),
                array(
                    'company_name' => 'Razão Social',
                    'cnpj' => 'CNPJ',
                    'responsible' => 'Responsável',
                    'email' => 'E-mail'
                )
            ));
            if ($error['error']) {
                return $this->sendError("Dados Incompletos.", $error['errors'], 401);
            }

            $input = $request->except(array('_token', 'active', 'userId'));
            $input['cnpj'] = preg_replace('/[^0-9]/', '', $input['cnpj']);
            $input['cep'] = preg_replace('/[^0-9]/', '', $input['cep']);
            $input['telephone'] = preg_replace('/[^0-9]/', '', $input['telephone']);

            $company = Company::where(array('id' => $id))->first();

            foreach ($input as $index => $value) {
                $company->$index = $value;
            }

            $company->save();

            return $this->sendResponse($company, "Empresa editada com sucesso.", "Grid::company");
        }

        return view("app/errors/access_denied", array(
            'user' => $user,
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        $user = $this->getAuthUser($request);
        if ($user->hasRole(['administrador', 'gerente'])) {
            if (!$id) {
                return ['status' => false, 'data' => [], 'message' => "Nenhum ID informado.", 'errors' => 'Empresa não removida.'];
            }

            $company = Company::where('id', $id)->first();
            $company->delete();

            return $this->sendResponse($company, "Empresa removida com sucesso.", "Grid::company");
        }

        return view("app/errors/access_denied", array(
            'user' => $user,
        ));
    }

    public function loadDataGrid(Request $request) {
        $total = 0;
        $result = array();

        $user = $this->getAuthUser($request);
        if ($user->hasRole(['administrador', 'gerente'])) {
            $f = $request->input('filters');
            $fs = explode("&", $f);
            $filters = null;

            foreach ($fs as $item) {
                $tmp = explode("=", $item);
                if ($tmp[1] != '') {
                    $filters[] = array(
                        'name' => $tmp[0],
                        'value' => $tmp[1]
                    );
                }
            }

            $columns = $request->input('columns');
            $order = $request->input('order');
            $start = $request->input('start');
            $length = $request->input('length');
            $page = ($start > 0) ? $start / $length : 1;
            $search = $request->input('search');

            $orderby = "companies.fantasy_name";
            $dir = "";
            if ($order) {
                $orderColumm = $columns[$order['0']['column']]['data'];
                if ($columns[$order['0']['column']]['data'] == 'idRegister') {
                    $orderColumm = "id";
                }
                $orderby = "companies." . $orderColumm;

                $dir = $order['0']['dir'];
            }

            if ($filters) {
                $total = Company::select("count(companies.*)")
                        ->orderby($orderby, $dir);
                foreach ($filters as $filter) {
                    $conditions[$filter['name']] = $filter['value'];
                    if ($filter['value'] != '') {
                        $total->orWhere($filter['name'], 'LIKE', '%' . $filter['value'] . '%');
                    }
                }
                $total = $total->distinct()->count();
            } else {
                $total = Company::select("count(*)")
                        ->orderby($orderby, $dir);
                $total = $total->distinct()->count();
            }

            if ($filters) {
                $data = Company::select("companies.*", "companies.fantasy_name", "companies.company_name", "companies.responsible", \DB::raw("LPAD(companies.id, 8, '0') as idRegister"), \DB::raw("format_cnpj(companies.cnpj) as cnpj"), \DB::raw('(CASE
                            WHEN companies.active = "0" THEN "Não"
                            WHEN companies.active = "1" THEN "Sim"
                            END) AS active'))
                        ->orderby($orderby, $dir);
                foreach ($filters as $filter) {
                    $conditions[$filter['name']] = $filter['value'];
                    if ($filter['value'] != '') {
                        $value = $filter['value'];
                        switch ($filter['name']) {
                            case 'companies.cnpj':
                                $value = preg_replace('/[^0-9]/', '', $value);
                                break;
                        }

                        $data->orWhere($filter['name'], 'LIKE', '%' . $value . '%');
                    }
                }

                $data = $data->limit($length)->offset($start)->distinct()->get();
            } else {
                $data = Company::select("companies.*", "companies.fantasy_name", "companies.company_name", "companies.responsible", \DB::raw("LPAD(companies.id, 8, '0') as idRegister"), \DB::raw("format_cnpj(companies.cnpj) as cnpj"), \DB::raw('(CASE
                    WHEN companies.active = "0" THEN "Não"
                    WHEN companies.active = "1" THEN "Sim"
                    END) AS active'))
                                ->orderby($orderby, $dir)
                                ->limit($length)->offset($start)->get();
            }

            $result = array();
            foreach ($data as $dt) {
                $result[] = array(
                    'fantasy_name' => $dt->fantasy_name,
                    'company_name' => $dt->company_name,
                    'responsible' => $dt->responsible,
                    'idRegister' => $dt->idRegister,
                    'cnpj' => $dt->cnpj,
                    'active' => $dt->active,
                    'action' => '<a href="#" class="" onclick="view(\'company\', \'' . $dt->id . '\');"><img src="img/view.png" style="width:15px"></a> '
                    . '/ <a href="#" onclick="edit(\'company\', \'' . $dt->id . '\');" class=""><img src="img/edit.png" style="width:15px"></a> '
                    . '/ <a href="#" onclick="remove(\'company\', \'' . $dt->id . '\');" class=""><img src="img/remove.png" style="width:15px"></a>',
                );
            }
        }

        return $this->sendResponseData(
                        array(
                            'recordsTotal' => $total,
                            'recordsFiltered' => $total,
                            'data' => $result,
        ));
    }

}
