<?php

namespace App\Http\Controllers\AVALIACAO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller {

    public function index(Request $request) {
        return view("avaliacao/home", array(
            'user' => $this->getAuthUser($request)
        ));
    }

}
