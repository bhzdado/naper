<?php

namespace App\Http\Controllers\API;

use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;

class PermissionController extends BaseController {

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
                'slug' => 'Permissão')
        ));
        if ($error['error']) {
            return $this->sendError($error['errors'], "Dados Incompletos.", 401);
        }

        $permission = Permission::create($request->all());
        return $this->sendResponse($permission, "Permissão cadastrada com sucesso.", "Grid::permission");
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
                'slug' => 'Permissão')
        ));

        if ($error['error']) {
            return ['status' => false, 'data' => [], 'message' => "Dados Incompletos.", 'errors' => $error['errors']];
        }

        $update = ['name' => $request->name, 'slug' => $request->slug];
        Permission::where('id', $id)->update($update);

        $permission = Permission::where('id', $id)->first();

        return $this->sendResponse($permission, "Permissão editado com sucesso.", "Grid::permission");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        if (!$id) {
            return ['status' => false, 'data' => [], 'message' => "Nenhum ID informado.", 'errors' => 'Permissão não removida.'];
        }

        $permission = Permission::where('id', $id)->first();
        $permission->delete();

        return $this->sendResponse($permission, "Permissão removida com sucesso.", "Grid::permission");
    }

    public function iniciarPermissao() {
        $user = Auth::user();
        //dd($user->hasPermission('administrator')); //will return true, if user has permission
        //dd($user->givePermissionsTo('create-user')); // will return permission, if not null
        //dd($user->can('create-user'));

        $create_user_permission = Permission::where('slug', 'create-permissions')->first();
        $create_permission_permission = Permission::where('slug', 'create-permissions')->first();

        $administrator_permission = Permission::where('slug', 'administrator')->first();
        if (!$administrator_permission) {
            $administrator_permission = new Permission();
            $administrator_permission->slug = 'administrator';
            $administrator_permission->name = 'Administrador';
            $administrator_permission->save();
            $administrator_permission->permissions()->attach($create_user_permission);
            $administrator_permission->permissions()->attach($create_permission_permission);
        }
        /*
          $manager_permission = Permission::where('slug', 'gerente')->first();
          if (!$manager_permission) {
          $manager_permission = new Permission();
          $manager_permission->slug = 'gerente';
          $manager_permission->name = 'Gerente';
          $manager_permission->save();
          }

          $supervisor_permission = Permission::where('slug', 'supervisor')->first();
          if (!$supervisor_permission) {
          $supervisor_permission = new Permission();
          $supervisor_permission->slug = 'supervisor';
          $supervisor_permission->name = 'Supervisor';
          $supervisor_permission->save();
          }

          $technical_permission = Permission::where('slug', 'technical')->first();
          if (!$technical_permission) {
          $technical_permission = new Permission();
          $technical_permission->slug = 'technical';
          $technical_permission->name = 'Técnico';
          $technical_permission->save();
          }

          $guest_permission = Permission::where('slug', 'guest')->first();
          if (!$guest_permission) {
          $guest_permission = new Permission();
          $guest_permission->slug = 'guest';
          $guest_permission->name = 'Visitante';
          $guest_permission->save();
          }
         */

        $createTasks = new Permission();
        $createTasks->slug = 'create-permissions';
        $createTasks->name = 'Cadastro de Permissões';
        $createTasks->save();
        $createTasks->permissions()->attach($administrator_permission);

        $editUsers = new Permission();
        $editUsers->slug = 'create-permissions';
        $editUsers->name = 'Cadastrar Papeis';
        $editUsers->save();
        $editUsers->permissions()->attach($administrator_permission);

        $create_permission = Permission::where('slug', 'create-permissions')->first();
        $create_permission = Permission::where('slug', 'create-permissions')->first();


        $administrator_permission = Permission::where('slug', 'administrator')->first();
        //$manager_permission = Permission::where('slug', 'gerente')->first();
        //$supervisor_permission = Permission::where('slug', 'supervisor')->first();
        //$technical_permission = Permission::where('slug', 'technical')->first();
        //$guest_permission = Permission::where('slug', 'guest')->first();

        $usuario = User::where('email', 'bhzdado@gmail.com')->first();
        $usuario->permissions()->attach($administrator_permission);
        $usuario->permissions()->attach($create_permission);
        $usuario->permissions()->attach($create_permission);

        return array(
            'status' => true,
            'data' => "Permissions ok");
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

        $orderby = "permissions.slug";
        $dir = "";
        if ($order) {
            $orderColumm = $columns[$order['0']['column']]['data'];
            if ($columns[$order['0']['column']]['data'] == 'idRegister') {
                $orderColumm = "id";
            }
            $orderby = "permissions." . $orderColumm;
            $dir = $order['0']['dir'];
        }

        if ($filters) {
            $total = Permission::select("count(permissions.*)");
            foreach ($filters as $filter) {
                $conditions[$filter['name']] = $filter['value'];
                if ($filter['value'] != '') {
                    $total->orWhere($filter['name'], 'LIKE', '%' . $filter['value'] . '%');
                }
            }
            $total = $total->distinct()->count();
        } else {
            $total = Permission::select("count(*)");
            $total = $total->distinct()->count();
        }

        if ($filters) {
            $data = Permission::select("permissions.*",
                    \DB::raw("LPAD(permissions.id, 8, '0') as idRegister"))
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
            $data = Permission::select("permissions.*",
                    \DB::raw("LPAD(permissions.id, 8, '0') as idRegister"))
                            ->orderby($orderby, $dir)
                            ->limit($length)->offset($start)->get();
        }

        $result = array();
        foreach ($data as $dt) {
            $result[] = array(
                'idRegister' => $dt->idRegister,
                'slug' => $dt->slug,
                'name' => $dt->name,
                'action' => '<a href="#" class="" onclick="view(\'permission\', \'' . $dt->id . '\');"><img src="img/view.png" style="width:15px"></a> '
                . '/ <a href="#" onclick="edit(\'permission\', \'' . $dt->id . '\');" class=""><img src="img/edit.png" style="width:15px"></a> '
                . '/ <a href="#" onclick="remove(\'permission\', \'' . $dt->id . '\');" class=""><img src="img/remove.png" style="width:15px"></a>',
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
