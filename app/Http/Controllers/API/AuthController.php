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

namespace App\Http\Controllers\API;

use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class AuthController extends BaseController
{
    const HTTP_OK = Response::HTTP_OK;
    const HTTP_CREATED = Response::HTTP_CREATED;
    const HTTP_UNAUTHORIZED = Response::HTTP_UNAUTHORIZED;

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'email' => 'required|email|max:255',
                    'password' => 'required|min:5',
                        ], [
                    'required' => 'O campo :attribute é obrigatório',
                        ], [
                    'email' => 'E-mail',
                    'password' => 'Senha',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Erro de validação', $validator->errors());
        }

        return self::authenticate($request->email, $request->password);
    }

    public static function authenticate($email, $password)
    {
        $credentials = [
            'email' => $email,
            'password' => $password
        ];

        $baseController = new BaseController();

        if (auth()->attempt($credentials)) {
            $user = Auth::user();
            $tokenName = $user->email . '-' . now();
            $token = self::get_user_token($user, $tokenName);
            $response = self::HTTP_OK;

            $oauth_access_tokens = DB::table('oauth_access_tokens')
                            ->where('user_id', $user->id)
                            ->orderBy('created_at', 'desc')->first();

            $info = "";
            if ($oauth_access_tokens) {
                if (DB::table('oauth_access_tokens')
                                ->where('id', '<>', $oauth_access_tokens->id)
                                ->delete()) {
                    $info = "<br><br><i>O seu usuário estava autenticado em outro navegador/IP e o mesmo foi finalizado.</i>";
                    auth()->user()->registerOut($user->id);
                }
            }

            DB::table('oauth_access_tokens')
                    ->where('user_id', $user->id)
                    ->update(array(
                        'expires_at' => date("Y-m-d H:i:s", strtotime("+30 minutes"))
            ));

            $tmp = explode(" ", $user->name);
            return $baseController->sendResponse(["accessToken" => $token, 'user' => $user], 'Bem-vindo, <b>' . $tmp[0] . "</b>. " . $info);
        } else {
            $error = "Unauthorized Access";
            $response = self::HTTP_UNAUTHORIZED;
            return $baseController->sendError('Erro na autenticação.', ["email" => "Usuário e senha não confere."], 401);
        }
    }

    public function validateToken(Request $request)
    {
        $baseController = new BaseController();
        return $baseController->sendResponse(array(), '');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $response = self::HTTP_OK;

        $request->user()->token()->revoke();
        $request->user()->token()->delete();

        DB::table('oauth_access_tokens')
                ->where('user_id', $user->id)
                ->delete();

        auth()->user()->registerOut($user->id);
        $baseController = new BaseController();
        return $baseController->sendResponse([], $user->name . ", você foi desconectado.<br> Até mais tarde!");
    }

    public function unauthenticated(Request $request)
    {
        $baseController = new BaseController();
        return $baseController->sendError(self::HTTP_UNAUTHORIZED, "Você não está autenticado.", 401);
    }

    public function expiredToken(Request $request)
    {
        $baseController = new BaseController();
        return $baseController->sendError(self::HTTP_UNAUTHORIZED, "O token de autenticação expirou. Faça login novamente.", 401);
    }

    public function detailsUserAuth()
    {
        $user = Auth::user();
        return $this->sendResponse($user);
    }

    public static function get_user_token($user, string $token_name = null)
    {
        return $user->createToken($token_name)->accessToken;
    }

    public function changePasswordProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'new_password' => 'required|min:6|max:12',
                    'c_password' => 'required|same:new_password|min:6|max:12',
                        ], [
                    'required' => 'O campo :attribute é obrigatório',
                        ], [
                    'new_password' => 'Nova Senha',
                    'c_password' => 'Confirmar Nova Senha',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Erro de validação', $validator->errors());
        }
        //$2y$10$ylhs6moZy4GrxaSRDvJR0eVZrtwQec8dEqZxC/AmTz1od7JDJfRQq
        //$2y$10$U6rwkqWgSqYYQf2Mqqmn1O0ZiFdVGzhP0Hypk70y2X07rYlt8aHFq
        //$2y$10$es.GjyuI9P6DlfrsapqquuE2mumE3CbgIpiw87xlSE3kgOHkgLJS2
        $id = ($request->input('idUser')) ? $request->input('idUser') : "";

        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];

        if (!auth()->attempt($credentials)) {
            return $this->sendError('Dados incorretos. Usuário não encontrado com a senha informada.', [], 401);
        }


        if ($request->input('new_password') != $request->input('c_password')) {
            return $this->sendError('A senha informada não confere com a confirmação inserida.', [], 401);
        }

        $user = Auth::user();
        $user->password = \Hash::make($request->input('new_password'));
        $user->save();

        return $this->sendResponse($user, $user->name . ", sua senha foi alterada com sucesso.");
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'new_password' => 'required|min:6|max:12',
                    'c_password' => 'required|same:new_password|min:6|max:12',
                        ], [
                    'required' => 'O campo :attribute é obrigatório',
                        ], [
                    'new_password' => 'Nova Senha',
                    'c_password' => 'Confirmar Nova Senha',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Erro de validação', $validator->errors());
        }

        $action = ($request->input('action')) ? $request->input('action') : "";
        $code = $request->input('activate_code');
        $tmp = explode("--", $code);
        $email = base64_decode($tmp[0]);
        $activation_code = $tmp[1];

        $user = User::where(array('email' => $email, 'activation_code' => $activation_code))->first();
        if (!$user) {
            return $this->sendError('Dados incorretos. Usuário não encontrado.', [], 401);
        }

        if ($user->active && !$action) {
            return $this->sendError('E-mail ja validado em ' . date("d/m/Y H:i:s", strtotime($user->email_verified_at)) . ".", [], 401);
        }

        if ($request->input('new_password') != $request->input('c_password')) {
            return $this->sendError('A senha informada não confere com a confirmação inserida.', [], 401);
        }

        if (!$action) {
            $user->active = 1;
            $user->email_verified_at = date('Y-m-d H:i:s');
        }
        $user->password = \Hash::make($request->input('new_password'));
        $user->save();

        Mail::to($user->email)->send(new SendMail($user, "change-password"));

        return $this->sendResponse($user, $user->name . ", sua senha foi alterada com sucesso.");
    }

    public function forgotPassword(Request $request, $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->sendError('Erro de validação', array("E-mail incorreto."));
        }

        $user = User::where(array('email' => $email))->first();
        $user->activation_code = md5($user->email . time());
        $user->save();

        Mail::to($user->email)->send(new SendMail($user, "forgot-password"));
        return $this->sendResponse($user, $user->name . ", enviamos para seu e-mail instruções de como cadastrar uma nova senha.");
    }
}
