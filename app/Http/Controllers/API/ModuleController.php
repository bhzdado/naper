<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Module;

class ModuleController extends BaseController {

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

        $orderby = "modules.name";
        $dir = "";
        if ($order) {
            $orderColumm = $columns[$order['0']['column']]['data'];
            if ($columns[$order['0']['column']]['data'] == 'idRegister') {
                $orderColumm = "id";
            }
            $orderby = "modules." . $orderColumm;
            $dir = $order['0']['dir'];
        }

        if ($filters) {
            $total = Module::select("count(modules.*)")
                    ->orderby($orderby, $dir)
                    ->join('tributes', 'tributes.id', 'modules.tribute_id');
            foreach ($filters as $filter) {
                $conditions[$filter['name']] = $filter['value'];
                if ($filter['value'] != '') {
                    $total->orWhere($filter['name'], 'LIKE', '%' . $filter['value'] . '%');
                }
            }
            $total = $total->distinct()->count();
        } else {
            $total = Module::select("count(*)")
                    ->orderby($orderby, $dir)
                    ->join('tributes', 'tributes.id', 'modules.tribute_id');
            $total = $total->distinct()->count();
        }

        if ($filters) {
            $data = Module::select("modules.*", \DB::raw("LPAD(modules.id, 8, '0') as idRegister"), "tributes.name as tribute_name", \DB::raw('(CASE
                            WHEN modules.published = "0" THEN "Não"
                            WHEN modules.published = "1" THEN "Sim"
                            END) AS published'))
                    ->join('tributes', 'tributes.id', 'modules.tribute_id')
                    ->orderby($orderby, $dir);
            foreach ($filters as $filter) {
                $conditions[$filter['name']] = $filter['value'];
                if ($filter['value'] != '') {
                    $value = $filter['value'];

                    $data->orWhere($filter['name'], 'LIKE', '%' . $value . '%');
                }
            }

            $data = $data->limit($length)->offset($start)->distinct()->get();
        } else {
            $data = Module::select("modules.*", \DB::raw("LPAD(modules.id, 8, '0') as idRegister"), "tributes.name as tribute_name", \DB::raw('(CASE
                    WHEN modules.published = "0" THEN "Não"
                    WHEN modules.published = "1" THEN "Sim"
                    END) AS published'))
                            ->join('tributes', 'tributes.id', 'modules.tribute_id')
                            ->orderby($orderby, $dir)
                            ->limit($length)->offset($start)->get();
        }

        $result = array();
        foreach ($data as $dt) {
            $result[] = array(
                'name' => $dt->name,
                'idRegister' => $dt->idRegister,
                'tribute_name' => $dt->tribute_name,
                'published' => $dt->published,
                'action' => '<a href="#" class="" onclick="view(\'module\', \'' . $dt->id . '\');"><img src="img/view.png" style="width:15px"></a> '
                . '/ <a href="#" onclick="edit(\'module\', \'' . $dt->id . '\');" class=""><img src="img/edit.png" style="width:15px"></a> '
                . '/ <a href="#" onclick="remove(\'module\', \'' . $dt->id . '\');" class=""><img src="img/remove.png" style="width:15px"></a>',
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
