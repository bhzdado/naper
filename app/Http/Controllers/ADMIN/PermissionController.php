<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\ADMIN;

use App\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Description of PermissionController
 *
 * @author RONALDO NASCIMENTO
 */
class PermissionController extends Controller {

    public function index(Request $request) {
        $user = $this->getAuthUser($request);
        if ($user->hasRole('root')) {
            return view("admin/default/index", array(
                'title' => 'Permissões',
                'route' => 'permissions/permission',
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
            return view("admin/permissions/permission/create");
        }

        return view("admin/errors/access_denied", array(
            'userAuth' => $user,
        ));
    }

    public function edit(Request $request, $id) {
        $user = $this->getAuthUser($request);
        if ($user->hasRole('root')) {
            $data['permission'] = Permission::where(array('id' => $id))->first();

            return view('admin/permissions/permission/create', $data);
        }

        return view("admin/errors/access_denied", array(
            'userAuth' => $user,
        ));
    }

    public function show(Request $request, $id) {
        return view('admin/permissions/permission/create', array(
            'action' => 'show', 
            'title' => 'Permissões',
            'roles' => Permission::orderBy('slug', 'asc')->get(),
            'permission' => Permission::select("permissions.*")
                    ->orderby('permissions.slug')
                    ->where('permissions.id', $id)
                    ->first()
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
                array('type' => 'text', 'name' => 'slug', 'title' => 'Permissão', 'width' => '30%'),
                array('type' => 'text', 'name' => 'name', 'title' => 'Descrição', 'width' => '40%'),
                array('type' => 'text', 'name' => 'action', 'title' => '', 'width' => '5%'),
            ),
            'filters' => array(
                array('type' => 'text', 'name' => 'permissions.slug', 'title' => 'Permissão', 'style' => 'width:60%'),
                array('type' => 'text', 'name' => 'permissions.name', 'title' => 'Descrição', 'style' => 'width:95%'),
            ),
            'permissions' => $permissions,
        );
        
         return view('admin/default/index', array(
            'title' => 'Permissões', 
            'route' => 'permission', 
            'userAuth' => $user,
            'permissions' => $permissions,
            'data' => $data,
        ));
    }
}
