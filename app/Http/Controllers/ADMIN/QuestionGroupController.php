<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuestionGroupController extends Controller {
    
    public function create(Request $request) {
        $user = $this->getAuthUser($request);
        if ($user->hasRole('administrador') || $user->hasRole('gerente') || $user->hasRole('funcionario')) {
            return view("admin/avaliacao/questionGroups/create", array(
                'title' => 'Assuntos',
                'userAuth' => $user,
                'answers' => array(),
            ));
        }

        return view("admin/errors/access_denied", array(
            'userAuth' => $user,
        ));
    }

    public function openGrid(Request $request) {
        $user = $this->getAuthUser($request);
        $permissions = array('create' => false, 'edit' => false, 'delete' => false, 'show' => true);
        if ($user->hasRole(['administrador'])) {
            $permissions = array('create' => true, 'edit' => true, 'delete' => true, 'show' => true);
        }

        $data = array(
            "fields" => array(
                array('type' => 'text', 'name' => 'idRegister', 'title' => 'Código', 'width' => '10%', 'align' => "center"),
                array('type' => 'text', 'name' => 'name', 'title' => 'Grupo', 'width' => '55%'),
                array('type' => 'text', 'name' => 'action', 'title' => '', 'width' => '5%'),
            ),
            'filters' => array(
                array('type' => 'text', 'name' => 'questionGroups.name', 'title' => 'Questão', 'style' => 'width:85%'),
            ),
            'permissions' => $permissions,
        );

        return view('admin/default/index', array(
            'title' => 'Assunto',
            'route' => 'avaliacao/questionGroup',
            'userAuth' => $user,
            'permissions' => $permissions,
            'data' => $data,
        ));
    }

}
