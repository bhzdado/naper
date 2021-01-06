<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Redirect,
    Response;

class ChartController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($chart) {
        return view('admin/charts/chart-js');
    }

}
