<?php

namespace App\Http\Controllers\API;

use App\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Menu as MenuResource;
use App\Http\Controllers\API\BaseController as BaseController;

class MenuController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $Menus = Menu::all();


        $data = null;
        $data = $this->loadData();

        
        /*
          var arrayjson = [
          {"id":"1", href": "http://home.com", "icon": "fas fa-home", "text": "Home", "target": "_top", "title": "My Home"},
          {"id":"2", "icon": "fas fa-chart-bar", "text": "Opcion2"},
          {"id":"3", "icon": "fas fa-bell", "text": "Opcion3"},
          {"id":"4", "icon": "fas fa-crop", "text": "Opcion4"},
          {"id":"5", "icon": "fas fa-flask", "text": "Opcion5"},
          {"id":"6", "icon": "fas fa-map-marker", "text": "Opcion6"},
          {"id":"7", "icon": "fas fa-search", "text": "Opcion7", "children": [
          {"id":"8", "icon": "fas fa-plug", "text": "Opcion7-1", "children": [
          {"id":"9", "icon": "fas fa-filter", "text": "Opcion7-1-1"}
          ]}
          ]}
          ];

          foreach($data as $menu){

          }
         * 
         */

        return $this->sendResponse(MenuResource::collection($data));
    }

    private function loadData($parent_id = null) {
        $data = array();
        $Menus = Menu::where('parent_id', $parent_id)->get();

        foreach ($Menus as $menu) {
            $dataMenu = array(
                'id' => $menu->id,
                'parent_id' => $menu->parent_id,
                'href' => $menu->href,
                'icon' => $menu->icon,
                'text' => $menu->text,
                'target' => $menu->target,
                'title' => $menu->title,
            );

            $children = $this->loadData($menu->id);
            if ($children) {
                $dataMenu['children'] = $children;
            }
            $data[] = $dataMenu;
        }

        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $dataJson = $request->all();
        $data = json_decode($dataJson['data'], true);

        $this->saveMenu($data);

        return $this->sendResponse([], "Menu cadastrado com sucesso.");
    }

    public $order = 1;

    private function saveMenu($menu, $parent_id = null) {
        if (!isset($menu['children'])) {
            if (is_array($menu)) {
                foreach ($menu as $data) {
                    $dataMenu = $data;
                    $dataMenu['parent_id'] = $parent_id;

                    $children = "";

                    if (isset($dataMenu['children'])) {
                        $children = $dataMenu['children'];
                    }
                    unset($dataMenu['children']);

                    if ($children) {
                        $parent_id = $this->addMenu($dataMenu);
                        return $this->saveMenu($children, $parent_id);
                    } else {
                        $this->addMenu($dataMenu);
                    }
                }
            }
        }
    }

    private function addMenu($dataMenu) {
        $dataMenu['order'] = $this->order;
        $this->order++;
        $id = (isset($dataMenu['id'])) ? $dataMenu['id'] : 0;
        if ($id) {
            Menu::where('id', $id)->update($dataMenu);
        } else {
            $dataMenu['id'] = 0;
            $menu = Menu::create($dataMenu);
            $id = $menu->id;
        }

        return $id;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        dd('Show');
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
                'slug' => 'required|max:255'),
            array('name' => 'Descrição',
                'slug' => 'Perfil')
        ));
        if ($error['error']) {
            return ['status' => false, 'data' => [], 'message' => "Dados Incompletos.", 'errors' => $error['errors']];
        }

        $update = ['name' => $request->name, 'slug' => $request->slug];
        Menu::where('id', $id)->update($update);
        $Menu = Menu::where('id', $id)->first();

        return $this->sendResponse($Menu, "Perfil editado com sucesso.", "Grid::permissions/Menu");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        if (!$id) {
            return ['status' => false, 'data' => [], 'message' => "Nenhum ID informado.", 'errors' => 'Perfil não removida.'];
        }

        $Menu = Menu::where('id', $id)->first();
        $Menu->delete();

        return $this->sendResponse($Menu, "Perfil removida com sucesso.", "Grid::permissions/Menu");
    }

}
