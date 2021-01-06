<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AuthController
 *
 * @author RONALDO NASCIMENTO
 */

namespace App\Http\Controllers\ADMIN;

use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\AuthController as ApiAuth;

class AuthController extends Controller {

    public function login(Request $request, $target = '') {
        return view('admin.login', ['errors' => null, 'target' => $request->input("tgt")]);
    }

    public function authenticate(Request $request) {
        $validate = $this->validations($request);

        $errors = '';
        if ($validate['error']) {
            $errors = $validate['errors'];
        } else {
            $data = ApiAuth::authenticate($request->email, $request->password);
            if (!$data['status']) {
                $errors = $data['errors'];
            } else {
                return redirect('admin');
            }
        }

        return view('admin.login', ['errors' => $errors]);
    }

    public function verifyEmail($code) {
        $tmp = explode("--", $code);
        $email = base64_decode($tmp[0]);
        $activation_code = $tmp[1];

        $user = User::where(array('email' => $email, 'activation_code' => $activation_code))->first();
        if (!$user) {
            throw new \Exception('Dados incorretos. Usuário não encontrado.', 530);
        }

        if ($user->active) {
            throw new \Exception('E-mail ja validado em ' . date("d/m/Y H:i:s", strtotime($user->email_verified_at) . "."), 530);
        }

        return view('admin.change_password', array("activate_code" => $code));
    }

    public function resetPassword($code) {
        $tmp = explode("--", $code);
        $email = base64_decode($tmp[0]);
        $activation_code = $tmp[1];

        $user = User::where(array('email' => $email, 'activation_code' => $activation_code))->first();
        if (!$user) {
            throw new \Exception('Dados incorretos. Usuário não encontrado.', 530);
        }

        return view('admin.change_password', array("activate_code" => $code, 'action' => "reset"));
    }

}
