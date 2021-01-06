<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Access;
use App\UsersRoles;
use Carbon\Carbon;

class UserController extends BaseController {

    static public function getUser($id) {
        return User::where('id', $id)->first();
    }

    public function userAccess(Request $request) {
        $data['title'] = "Acesso de usuário por dia";
        $data['type'] = 'bar';

        $record = Access::select(
                        \DB::raw("COUNT(*) as count"), \DB::raw("DATE_FORMAT(accesses.datetime_login, '%Y-%m-%d') as day_login"), \DB::raw("accesses.user_id"
                ))
                ->groupBy('day_login', 'accesses.user_id')
                ->orderBy('accesses.user_id')
                ->get();

        foreach ($record as $row) {
            $data['label'][] = date('d/m/Y', strtotime($row->day_login));
            $data['data'][] = (int) $row->count;
        }

        //$data['chart_data'] = json_encode($data);
        return $this->sendResponseData(array('success' => true, 'chart_data' => $data));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    public function show($id) {
        $user = User::where('id', $id)->first();

        if (!$user) {
            return $this->sendError("Usuário não encontrado.", [], 401);
        }

        return $this->sendResponse(array(
                    "id" => $id,
                    "name" => $user->name,
                    "email" => $user->email,
                    "avatar" => $user->avatar,
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $error = $this->ValidatorMake($request, array(
            array('name' => 'required|max:255',
                'email' => 'required|email|max:255'),
            array('name' => 'Nome',
                'email' => 'E-mail')
        ));

        if ($error['error']) {
            return $this->sendError("Dados Incompletos.", $error['errors'], 401);
        }

        $role_id = $request->input("role_id");

        $input = $request->except(array('_token', 'role_id'));
        $input['cpf'] = preg_replace('/[^0-9]/', '', $input['cpf']);
        $input['cnpj'] = preg_replace('/[^0-9]/', '', $input['cnpj']);
        $input['cep'] = preg_replace('/[^0-9]/', '', $input['cep']);
        $input['telephone'] = preg_replace('/[^0-9]/', '', $input['telephone']);
        $input['cellphone'] = preg_replace('/[^0-9]/', '', $input['cellphone']);

        $input['password'] = \Hash::make($this->generatePassword(8, true, true, true, true));
        $input['activation_code'] = md5($input['email'] . time());

        if ($this->isDuplicateEmail($input['email'])) {
            return $this->sendError("E-mail cadastrado.", array("O E-mail informado já está cadastrado no sistema."), 501);
        }

        $user = User::create($input);
        return $this->sendResponse($user, "Usuário cadastrado com sucesso.", "Grid::user");
    }

    private function isDuplicateEmail($email) {
        $user = User::where(array('email' => $email))->first();
        if ($user) {
            return true;
        }

        return false;
    }

    private function generatePassword($size, $uppercase, $lowercase, $numbers, $symbols) {
        $ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ"; // $ma contem as letras maiúsculas
        $mi = "abcdefghijklmnopqrstuvyxwz"; // $mi contem as letras minusculas
        $nu = "0123456789"; // $nu contem os números
        $si = "!@#$%¨&*()_+="; // $si contem os símbolos

        $password = '';

        if ($uppercase) {
            // se $uppercase for "true", a variável $ma é embaralhada e adicionada para a variável $password
            $password .= str_shuffle($ma);
        }

        if ($lowercase) {
            // se $lowercase for "true", a variável $mi é embaralhada e adicionada para a variável $password
            $password .= str_shuffle($mi);
        }

        if ($numbers) {
            // se $numbers for "true", a variável $nu é embaralhada e adicionada para a variável $password
            $password .= str_shuffle($nu);
        }

        if ($symbols) {
            // se $symbols for "true", a variável $si é embaralhada e adicionada para a variável $password
            $password .= str_shuffle($si);
        }

        // retorna a senha embaralhada com "str_shuffle" com o tamanho definido pela variável $size
        return substr(str_shuffle($password), 0, $size);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $error = $this->ValidatorMake($request, array(
            array('name' => 'required|max:255',
                'email' => 'required|email|max:255'),
            array('name' => 'Nome',
                'email' => 'E-mail')
        ));
        if ($error['error']) {
            return ['status' => false, 'data' => [], 'message' => "Dados Incorretos.", 'errors' => $error['errors']];
        }

        $role_id = $request->input("role_id");

        $input = $request->except(array('_token', 'role_id', 'userId'));
        $input['cpf'] = preg_replace('/[^0-9]/', '', $input['cpf']);
        $input['cep'] = preg_replace('/[^0-9]/', '', $input['cep']);
        $input['telephone'] = preg_replace('/[^0-9]/', '', $input['telephone']);
        $input['cellphone'] = preg_replace('/[^0-9]/', '', $input['cellphone']);

        if ($input['date_birth'] != null) {
            $date_birth = explode("/", $input['date_birth']);
            $input['date_birth'] = $date_birth[2] . '-' . $date_birth[1] . '-' . $date_birth[0];
        }

        $user = User::where(array('id' => $id))->first();

        foreach ($input as $index => $value) {
            $user->$index = $value;
        }

        $user->save();

        return $this->sendResponse($user, "Usuário editado com sucesso.", "Grid::user");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        if (!$id) {
            return ['status' => false, 'data' => [], 'message' => "Nenhum ID informado.", 'errors' => 'Usuário não removido.'];
        }

        $user = User::where('id', $id)->first();
        $user->delete();

        return $this->sendResponse($user, "Usuário removido com sucesso.", "Grid::user");
    }

    public function loadDataGrid(Request $request) {
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

        $dir = $order['0']['dir'];
        $order = $columns[$order['0']['column']];

        if ($filters) {
            $total = User::select("count(users.*)")
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->leftjoin('companies', 'companies.id', 'users.company_id');
            foreach ($filters as $filter) {
                $conditions[$filter['name']] = $filter['value'];
                if ($filter['value'] != '') {
                    $total->orWhere($filter['name'], 'LIKE', '%' . $filter['value'] . '%');
                }
            }
            $total = $total->distinct()->count();
        } else {
            $total = User::select("count(*)")
                   ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->leftjoin('companies', 'companies.id', 'users.company_id');
            $total = $total->distinct()->count();
        }

        if ($filters) {
            $data = User::select(
                            \DB::raw("LPAD(users.id, 8, '0') as idRegister"), "users.name", "users.id", "companies.company_name", "users.email", "roles.name as role_name", \DB::raw('(CASE
                    WHEN users.active = "0" THEN "Não"
                    WHEN users.active = "1" THEN "Sim"
                    END) AS active')
                    )
                    ->join('roles', 'users.role_id', '=', 'roles.id')
                    ->leftjoin('companies', 'companies.id', 'users.company_id')
                    ->orderby($order['data'], $dir);

            foreach ($filters as $filter) {
                $conditions[$filter['name']] = $filter['value'];
                if ($filter['value'] != '') {
                    $data->orWhere($filter['name'], 'LIKE', '%' . $filter['value'] . '%');
                }
            }

            $data = $data->limit($length)->offset($start)->distinct()->get();
        } else {
            $data = User::select(
                                    \DB::raw("LPAD(users.id, 8, '0') as idRegister"), "users.id", "users.name", "companies.company_name", "users.email", "roles.name as role_name", \DB::raw('(CASE
                    WHEN users.active = "0" THEN "Não"
                    WHEN users.active = "1" THEN "Sim"
                    END) AS active')
                            )
                            ->join('roles', 'users.role_id', '=', 'roles.id')
                            ->leftjoin('companies', 'companies.id', 'users.company_id')
                            ->orderby($order['data'], $dir)
                            ->limit($length)->offset($start)->get();
        }

        $result = array();
        foreach ($data as $dt) {
            $result[] = array(
                'idRegister' => $dt->idRegister,
                'company_name' => $dt->company_name,
                'name' => $dt->name,
                'email' => $dt->email,
                'role_name' => $dt->role_name,
                'active' => $dt->active,
                'action' => '<a href="#" class="" onclick="view(\'user\', \'' . $dt->id . '\');"><img src="img/view.png" style="width:15px"></a> '
                . '/ <a href="#" onclick="edit(\'user\', \'' . $dt->id . '\');" class=""><img src="img/edit.png" style="width:15px"></a> '
                . '/ <a href="#" onclick="remove(\'user\', \'' . $dt->id . '\');" class=""><img src="img/remove.png" style="width:15px"></a>',
            );
        }

        return $this->sendResponseData(
                        array(
                            'recordsTotal' => $total,
                            'recordsFiltered' => $total,
                            'data' => $result,
        ));
    }

    public function saveAvatar(Request $request, $id) {
        $valid_extensions = array("jpg", "jpeg", "png", "gif");

        if (!$request->hasFile('image')) {
            return $this->sendError("Arquivo de imagem não encontrado.", array("Nenhum arquivo de imagem informado."), 501);
        }

        if (!$request->file('image')->isValid()) {
            return $this->sendError("Arquivo inválido.", array("O Arquivo informado é inválido."), 501);
        }

        $extension = $request->image->extension();
        $filename = str_pad($id, 8, "0", STR_PAD_LEFT) . '.' . $extension;

        if (!in_array(strtolower($extension), $valid_extensions)) {
            return $this->sendError("Arquivo inválido.", array("O Arquivo informado não é uma arquivo de imagem válida."), 501);
        }

        $upload = $request->image->storeAs('public/avatars', $filename);
        if (!$upload) {
            return $this->sendError("Erro.", array("Erro ao tentar fazer o upload da foto. Favor tentar novamente."), 501);
        }

        $user = User::where(array('id' => $id))->first();
        $user->avatar = $filename;
        $user->save();

        return $this->sendResponse(array('filename' => "storage/avatars-" . $filename), "Foto alterada com sucesso.");
    }

}
