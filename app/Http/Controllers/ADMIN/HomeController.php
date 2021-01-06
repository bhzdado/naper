<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller {

    public function index(Request $request) {
        return view("admin/home", array(
            'user' => $this->getAuthUser($request)
        ));
    }

    public function dashboard(Request $request) {
        return view("admin/home/dashboard", array(
            'user' => $this->getAuthUser($request)
        ));
    }

    public function menu(Request $request) {
        return view("admin/layouts/includes/sidebar_menu", array(
            'user' => $this->getAuthUser($request)
        ));
    }

}
