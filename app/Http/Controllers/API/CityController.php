<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\City;

class CityController extends BaseController {

    public function searchCep(Request $request) {
        $city = null;
        $cep = str_replace(".", "", str_replace("-", "", $request->input('cep')));

        $ch = curl_init('https://viacep.com.br/ws/' . $cep . '/json/');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json')
        );

        $result = json_decode(curl_exec($ch));
        curl_close($ch);

        if ($result === null) {
            return $this->sendError("Impossivel verificar o cep.", array("Tente novamente e/ou verifique sua conexão da internet."), 401);
        } else {
            if (!isset($result->erro) || !$result->erro) {
                $city = City::where(array('iso' => $result->ibge))->first();
            }

            if ($city) {
                return $this->sendResponse(array(
                            'name' => $city->name,
                            'city_id' => $city->id,
                            'state_id' => $city->state_id,
                            'state' => $city->state->name,
                            'letter' => $city->state->letter,
                            'address' => $result->logradouro,
                            'neighborhood' => $result->bairro,
                ));
            }
        }

        return $this->sendError("Dados incorretos.", array("Cep informado não encontrado."), 401);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}
