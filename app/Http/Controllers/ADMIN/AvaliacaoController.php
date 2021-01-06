<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AvaliacaoController extends Controller {

    public function index(Request $request) {
        return view("admin/avaliacao/home", array(
            'user' => $this->getAuthUser($request)
        ));
    }
}
