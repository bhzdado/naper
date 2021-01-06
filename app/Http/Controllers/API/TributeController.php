<?php

namespace App\Http\Controllers\APi;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tribute;

class TributeController extends BaseController {

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
        $error = $this->ValidatorMake($request, array(
            array('name' => 'required|max:255',
                'published' => 'required|max:255'),
            array('name' => 'Imposto',
                'published' => 'Publicado')
        ));
        if ($error['error']) {
            return $this->sendError("Dados Incompletos.", $error['errors'], 401);
        }

        $tribute = Tribute::create($request->all());
        return $this->sendResponse($tribute, "Imposto cadastrado com sucesso.", "Grid::tribute");
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
        $error = $this->ValidatorMake($request, array(
            array('name' => 'required|max:255',
                'published' => 'required|max:255'),
            array('name' => 'Imposto',
                'published' => 'Publicado')
        ));

        if ($error['error']) {
            return $this->sendError("Dados Incompletos.", $error['errors'], 401);
        }

        $update = ['name' => $request->name, 'published' => $request->published];
        Tribute::where('id', $id)->update($update);
        $tribute = Tribute::where('id', $id)->first();

        return $this->sendResponse($tribute, "Imposto editado com sucesso.", "Grid::tribute");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        if (!$id) {
            return ['status' => false, 'data' => [], 'message' => "Nenhum ID informado.", 'errors' => 'Imposto não removido.'];
        }

        $tribute = Tribute::where('id', $id)->first();
        $tribute->delete();

        return $this->sendResponse($tribute, "Imposto removido com sucesso.", "Grid::tribute");
    }

    public function loadDataGrid(Request $request) {
        $f = $request->input('filters');
        $fs = explode("&", $f);
        $filters = null;

        foreach ($fs as $item) {
            $tmp = explode("=", $item);
            if ($tmp[1] != '') {
                $filters[] = array(
                    'name' => $tmp[0],
                    'value' => $tmp[1]
                );
            }
        }

        $columns = $request->input('columns');
        $order = $request->input('order');
        $start = $request->input('start');
        $length = $request->input('length');
        $page = ($start > 0) ? $start / $length : 1;
        $search = $request->input('search');

        $orderby = "tributes.name";
        $dir = "";
        if ($order) {
            $orderColumm = $columns[$order['0']['column']]['data'];
            if ($columns[$order['0']['column']]['data'] == 'idRegister') {
                $orderColumm = "id";
            }
            $orderby = "tributes." . $orderColumm;
            $dir = $order['0']['dir'];
        }

        if ($filters) {
            $total = Tribute::select("count(tributes.*)")
                    ->orderby($orderby, $dir);
            foreach ($filters as $filter) {
                $conditions[$filter['name']] = $filter['value'];
                if ($filter['value'] != '') {
                    $total->orWhere($filter['name'], 'LIKE', '%' . $filter['value'] . '%');
                }
            }
            $total = $total->distinct()->count();
        } else {
            $total = Tribute::select("count(*)")
                    ->orderby($orderby, $dir);
            $total = $total->distinct()->count();
        }

        if ($filters) {
            $data = Tribute::select("tributes.*", \DB::raw("LPAD(tributes.id, 8, '0') as idRegister"), \DB::raw('(CASE
                            WHEN tributes.published = "0" THEN "Não"
                            WHEN tributes.published = "1" THEN "Sim"
                            END) AS published'))
                    ->orderby($orderby, $dir);
            foreach ($filters as $filter) {
                $conditions[$filter['name']] = $filter['value'];
                if ($filter['value'] != '') {
                    $value = $filter['value'];
                    switch ($filter['name']) {
                        case 'tributes.cnpj':
                            $value = preg_replace('/[^0-9]/', '', $value);
                            break;
                    }

                    $data->orWhere($filter['name'], 'LIKE', '%' . $value . '%');
                }
            }

            $data = $data->limit($length)->offset($start)->distinct()->get();
        } else {
            $data = Tribute::select("tributes.*", \DB::raw("LPAD(tributes.id, 8, '0') as idRegister"), \DB::raw('(CASE
                    WHEN tributes.published = "0" THEN "Não"
                    WHEN tributes.published = "1" THEN "Sim"
                    END) AS published'))
                            ->orderby($orderby, $dir)
                            ->limit($length)->offset($start)->get();
        }

        $result = array();
        foreach ($data as $dt) {
            $result[] = array(
                'name' => $dt->name,
                'idRegister' => $dt->idRegister,
                'published' => $dt->published,
                'action' => '<a href="#" class="" onclick="view(\'tribute\', \'' . $dt->id . '\');"><img src="img/view.png" style="width:15px"></a> '
                . '/ <a href="#" onclick="edit(\'tribute\', \'' . $dt->id . '\');" class=""><img src="img/edit.png" style="width:15px"></a> '
                . '/ <a href="#" onclick="remove(\'tribute\', \'' . $dt->id . '\');" class=""><img src="img/remove.png" style="width:15px"></a>',
            );
        }

        return $this->sendResponseData(
                        array(
                            'recordsTotal' => $total,
                            'recordsFiltered' => $total,
                            'data' => $result,
        ));
    }

}
