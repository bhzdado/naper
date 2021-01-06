<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Tribute;
use App\Module;

class ModuleController extends Controller {
    
    public function openGrid(Request $request) {
        $user = $this->getAuthUser($request);
        $permissions = array('create' => false, 'edit' => false, 'delete' => false, 'show' => true);
        if ($user->hasRole('administrador')) {
            $permissions = array('create' => true, 'edit' => true, 'delete' => true, 'show' => true);
        }

        $data = array(
            "fields" => array(
                array('type' => 'text', 'name' => 'idRegister', 'title' => 'Código', 'width' => '10%', 'align' => "center"),
                array('type' => 'text', 'name' => 'tribute_name', 'title' => 'Imposto', 'width' => '40%'),
                array('type' => 'text', 'name' => 'name', 'title' => 'Nome', 'width' => '40%'),
                array('type' => 'text', 'name' => 'published', 'title' => 'Publicado', 'width' => '10%'),
                array('type' => 'text', 'name' => 'action', 'title' => '', 'width' => '5%'),
            ),
            'filters' => array(
                array('type' => 'text', 'name' => 'modules.name', 'title' => 'Nome', 'style' => 'width:85%'),
                array('type' => 'text', 'name' => 'tritubes.name', 'title' => 'Imposto', 'style' => 'width:85%'),
                array('type' => 'select', 'name' => 'modules.published', 'title' => 'Publicado', 'style' => 'width:15%', 'options' => array("0" => "Não", "1" => "Sim")),
            ),
            'permissions' => $permissions,
        );

        return view('admin/default/index', array(
            'title' => 'Módulos',
            'route' => 'module',
            'userAuth' => $user,
            'permissions' => $permissions,
            'data' => $data,
        ));
    }
    
    public function create(Request $request) {
        $user = $this->getAuthUser($request);
        if ($user->hasRole('administrador') || $user->hasRole('gerente') || $user->hasRole('funcionario')) {
            return view("admin/modules/create", array(
                'title' => 'Módulos',
                'userAuth' => $user,
            ));
        }

        return view("admin/errors/access_denied", array(
            'userAuth' => $user,
        ));
    }
    
    public function edit(Request $request, $id) {
        $user = $this->getAuthUser($request);
        if ($user->hasRole('administrador')) {
            $where = array('id' => $id);
            $data['tribute'] = Module::where($where)->first();

            return view('admin/modules/create', $data);
        }

        return view("admin/errors/access_denied", array(
            'userAuth' => $user,
        ));
    }
    
    public function show(Request $request, $id) {
         return view('admin/modules/create', array(
            'action' => 'show', 
            'title' => 'Imposto',
            'tribute' => Module::select("modules.*")
                    ->orderby('modules.name')
                    ->where('modules.id', $id)
                    ->first()
        ));

        return view("admin/errors/access_denied", array(
            'userAuth' => $user,
        ));
    }
}
