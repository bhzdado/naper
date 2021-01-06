<?php

namespace App\Http\Controllers\ADMIN;

use App\Permission;
use App\Role;
use App\RolesPermissions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller {

    public function index(Request $request) {
        $user = $this->getAuthUser($request);
        if ($user->hasRole('root')) {
            return view("admin/default/index", array(
                'title' => 'Perfis',
                'route' => 'permissions/role',
                'userAuth' => $user,
            ));
        }

        return view("admin/errors/access_denied", array(
            'user' => $user,
        ));
    }

    public function create(Request $request) {
        $user = $this->getAuthUser($request);
        if ($user->hasRole('root')) {
            $data['permissions'] = Permission::orderBy('id', 'asc')->get();
            return view("admin/permissions/role/create", $data);
        }

        return view("admin/errors/access_denied", array(
            'userAuth' => $user,
        ));
    }

    public function edit(Request $request, $id) {
        $user = $this->getAuthUser($request);
        if ($user->hasRole('root')) {
            $where = array('id' => $id);
            $data['role'] = Role::where($where)->first();

            $data['permissions'] = Permission::orderBy('id', 'asc')->get();
            $data['rolesPermissions'] = RolesPermissions::orderBy('id', 'asc')->get();

            return view('admin/permissions/role/create', $data);
        }

        return view("admin/errors/access_denied", array(
            'userAuth' => $user,
        ));
    }

    public function show(Request $request, $id) {
         return view('admin/permissions/role/create', array(
            'action' => 'show', 
            'title' => 'Perfis',
            'role' => Role::select("roles.*")
                    ->orderby('roles.slug')
                    ->where('roles.id', $id)
                    ->first()
        ));

        return view("admin/errors/access_denied", array(
            'userAuth' => $user,
        ));
    }

    public function openGrid(Request $request) {
        $user = $this->getAuthUser($request);
        $permissions = array('create' => false, 'edit' => false, 'delete' => false, 'show' => true);
        if ($user->hasRole('root')) {
            $permissions = array('create' => true, 'edit' => true, 'delete' => true, 'show' => true);
        }
       
        $data = array(
            "fields" => array(
                array('type' => 'text', 'name' => 'idRegister', 'title' => 'Código', 'width' => '10%', 'align' => "center"),
                array('type' => 'text', 'name' => 'name', 'title' => 'Nome', 'width' => '40%'),
                array('type' => 'text', 'name' => 'slug', 'title' => 'Perfil', 'width' => '30%'),
                array('type' => 'text', 'name' => 'action', 'title' => '', 'width' => '5%'),
            ),
            'filters' => array(
                array('type' => 'text', 'name' => 'roles.name', 'title' => 'Nome', 'style' => 'width:95%'),
                array('type' => 'text', 'name' => 'roles.slug', 'title' => 'Perfil', 'style' => 'width:60%'),
            ),
            'permissions' => $permissions,
        );
        
         return view('admin/default/index', array(
            'title' => 'Perfis', 
            'route' => 'role', 
            'userAuth' => $user,
            'permissions' => $permissions,
            'data' => $data,
        ));
    }
}
