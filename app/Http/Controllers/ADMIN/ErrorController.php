<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers\ADMIN;

use Illuminate\Http\Request;

/**
 * Description of ErrorController
 *
 * @author RONALDO NASCIMENTO
 */
class ErrorController {
    public function nomobile(Request $request) {
        return view("admin/errors/nomobile");
    }
}
