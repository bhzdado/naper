<?php

namespace App\Http\Controllers\API;

use App\Role;
use App\RolesRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Role as RoleResource;
use App\Http\Controllers\API\BaseController as BaseController;

class RoleController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $user = $this->getAuthUser($request);
        if ($user->hasRole('administrador')) {
            $roles = Role::all();
            return $this->sendResponse(RoleResource::collection($roles));
        }

        return view("app/errors/access_denied", array(
            'user' => $user,
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
                'slug' => 'required|max:255'),
            array('name' => 'Descrição',
                'slug' => 'Perfil')
        ));
        if ($error['error']) {
            return $this->sendError("Dados Incompletos.", $error['errors'], 401);
        }

        $role = Role::create($request->all());

        if ($request->roles) {
            $roles = RolesRoles::where('role_id', $role->id)->get();
            foreach ($roles as $roleRole) {
                $roleRole->delete();
            }

            $roles = $request->roles;
            foreach ($roles as $id) {
                RolesRoles::create(array(
                    'role_id' => $role->id,
                    'role_id' => $id
                ));
            }
        }

        return $this->sendResponse($role, "Perfil cadastrado com sucesso.", "Grid::role");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        dd('Show');
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
                'slug' => 'required|max:255'),
            array('name' => 'Descrição',
                'slug' => 'Perfil')
        ));
        if ($error['error']) {
            return ['status' => false, 'data' => [], 'message' => "Dados Incompletos.", 'errors' => $error['errors']];
        }

        $update = ['name' => $request->name, 'slug' => $request->slug];
        Role::where('id', $id)->update($update);
        $role = Role::where('id', $id)->first();

        if ($request->roles) {
            $roles = RolesRoles::where('role_id', $role->id)->get();
            foreach ($roles as $role) {
                $role->delete();
            }

            $roles = $request->roles;
            foreach ($roles as $id) {
                $role = RolesRoles::create(array(
                            'role_id' => $role->id,
                            'role_id' => $id
                ));
            }
        }

        return $this->sendResponse($role, "Perfil editado com sucesso.", "Grid::role");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        if (!$id) {
            return ['status' => false, 'data' => [], 'message' => "Nenhum ID informado.", 'errors' => 'Perfil não removida.'];
        }

        $role = Role::where('id', $id)->first();
        $role->delete();

        return $this->sendResponse($role, "Perfil removida com sucesso.", "Grid::role");
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

        $orderby = "roles.name";
        $dir = "";
        if ($order) {
            $orderColumm = $columns[$order['0']['column']]['data'];
            if ($columns[$order['0']['column']]['data'] == 'idRegister') {
                $orderColumm = "id";
            }
            $orderby = "roles." . $orderColumm;
            $dir = $order['0']['dir'];
        }

        if ($filters) {
            $total = Role::select("count(roles.*)");
            foreach ($filters as $filter) {
                $conditions[$filter['name']] = $filter['value'];
                if ($filter['value'] != '') {
                    $total->orWhere($filter['name'], 'LIKE', '%' . $filter['value'] . '%');
                }
            }
            $total = $total->distinct()->count();
        } else {
            $total = Role::select("count(*)");
            $total = $total->distinct()->count();
        }

        if ($filters) {
            $data = Role::select("roles.*",
                    \DB::raw("LPAD(roles.id, 8, '0') as idRegister"))
                    ->orderby($orderby, $dir);
            foreach ($filters as $filter) {
                $conditions[$filter['name']] = $filter['value'];
                if ($filter['value'] != '') {
                    $value = $filter['value'];
                    $data->orWhere($filter['name'], 'LIKE', '%' . $value . '%');
                }
            }

            $data = $data->limit($length)->offset($start)->distinct()->get();
        } else {
            $data = Role::select("roles.*",
                    \DB::raw("LPAD(roles.id, 8, '0') as idRegister"))
                            ->orderby($orderby, $dir)
                            ->limit($length)->offset($start)->get();
        }

        $result = array();
        foreach ($data as $dt) {
            $result[] = array(
                'idRegister' => $dt->idRegister,
                'slug' => $dt->slug,
                'name' => $dt->name,
                'action' => '<a href="#" class="" onclick="view(\'role\', \'' . $dt->id . '\');"><img src="img/view.png" style="width:15px"></a> '
                . '/ <a href="#" onclick="edit(\'role\', \'' . $dt->id . '\');" class=""><img src="img/edit.png" style="width:15px"></a> '
                . '/ <a href="#" onclick="remove(\'role\', \'' . $dt->id . '\');" class=""><img src="img/remove.png" style="width:15px"></a>',
            );
        }

        return $this->sendResponseData(
                        array(
                            'recordsTotal' => $total,
                            'recordsFiltered' => $total,
                            'data' => $result,
        ));
    }
}
