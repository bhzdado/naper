<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Role;

class UserController extends Controller {

    public function create(Request $request) {
        $user = $this->getAuthUser($request);
        if ($user->hasRole('administrador') || $user->hasRole('gerente') || $user->hasRole('funcionario')) {
            return view("admin/users/create", array(
                'title' => 'Usuários',
                'roles' => Role::orderBy('name', 'asc')->get(),
                'userAuth' => $user,
            ));
        }

        return view("admin/errors/access_denied", array(
            'userAuth' => $user,
        ));
    }

    public function edit(Request $request, $id) {
        $user = $this->getAuthUser($request);
        if ($user->hasRole(['administrador', 'gerente', 'funcionario'])) {
            return view('admin/users/create', array(
                'title' => 'Usuários',
                'roles' => Role::orderBy('name', 'asc')->get(),
                'user' => User::select("users.*", "roles.id as role_id", "roles.name as role_name")
                        ->join('roles', 'users.role_id', '=', 'roles.id')
                        ->orderby('users.name')
                        ->where('users.id', $id)
                        ->first()
            ));
        }

        return view("admin/errors/access_denied", array(
            'userAuth' => $user,
        ));
    }

    public function show(Request $request, $id) {
        $user = $this->getAuthUser($request);
        if ($user->hasRole(['administrador', 'gerente', 'funcionario'])) {
            return view('admin/users/create', array(
                'action' => 'show',
                'title' => 'Usuários',
                'roles' => Role::orderBy('name', 'asc')->get(),
                'user' => User::select("users.*", "roles.id as role_id", "roles.name as role_name")
                        ->join('roles', 'users.role_id', '=', 'roles.id')
                        ->orderby('users.name')
                        ->where('users.id', $id)
                        ->first()
            ));
        }

        return view("admin/errors/access_denied", array(
            'userAuth' => $user,
        ));
    }

    public function profile(Request $request, $id) {
        return view('admin/users/profile', array(
            'user' => User::where(array('id' => $id))->first()
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
                array('type' => 'text', 'name' => 'name', 'title' => 'Nome', 'width' => '40%'),
                array('type' => 'text', 'name' => 'company_name', 'title' => 'Empresa', 'width' => '30%'),
                array('type' => 'text', 'name' => 'email', 'title' => 'E-mail', 'width' => '30%'),
                array('type' => 'text', 'name' => 'role_name', 'title' => 'Perfil', 'width' => '25%'),
                array('type' => 'text', 'name' => 'active', 'title' => 'Ativo', 'width' => '5%'),
                array('type' => 'text', 'name' => 'action', 'title' => '', 'width' => '5%'),
            ),
            'filters' => array(
                array('type' => 'text', 'name' => 'users.name', 'title' => 'Nome', 'style' => 'width:95%'),
                array('type' => 'text', 'name' => 'companies.company_name', 'title' => 'Empresa', 'style' => 'width:60%'),
                array('type' => 'text', 'name' => 'users.email', 'title' => 'E-mail', 'style' => 'width:60%'),
            ),
            'permissions' => $permissions,
        );

        return view('admin/default/index', array(
            'title' => 'Usuários',
            'route' => 'user',
            'userAuth' => $user,
            'permissions' => $permissions,
            'data' => $data,
        ));
    }

}
