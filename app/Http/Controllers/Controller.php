<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\UserController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;

class Controller extends BaseController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;
    
    public function getAuthUser(Request $request) {
        return UserController::getUser($request->input("userId"));
    }

    private $user = null;
    public function ValidatorMake($request, $data) {

        $errors = [];
        $error = false;

        $validator = Validator::make($request->all(), $data[0], array('required' => '- O campo :attribute é obrigatório'), $data[1]);

        if ($validator->fails()) {
            $error = true;
            $data = $validator->errors();
            foreach ($data->getMessages() as $error) {
                $errors[] = $error[0];
            }
        }

        return ["error" => $error, "errors" => $errors];
    }

    public function openGrid(Request $request) {
        throw new \Exception('Nenhum dado configurado nesta view');
    }
}
