<?php

namespace App\Http\Controllers\ADMIN;

use App\Http\Controllers\Controller;
use App\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller {

    /**
     * @var Menu
     */
    private $menu;

    public function __construct(Menu $menu) {
        $this->menu = $menu;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $user = $this->getAuthUser($request);

        $permissions = array('create' => false);
        if ($user->hasRole('administrador') || $user->hasRole('gerente')) {
            $permissions = array('create' => true);
        }

        if ($user->hasRole('administrador')) {
            return view("admin/menus/index", array(
                'title' => 'Administração de Menus',
                'route' => 'menu',
                'userAuth' => $user,
                'permissions' => $permissions,
            ));
        }

        return view("admin/errors/access_denied", array(
            'user' => $user,
        ));
    }

    /**
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id) { // Método que exibe o menu selecionado
        $menu = Menu::where(array('id' => $id))->first();
        return view('admin.menus.show', compact('menu'));
    }

    /**
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($id = null) { // Método que exibe o formulário de cadastro de menu
        // O recebimento deste parâmetro foi de minha opção. Fiz isso para facilitar a inserção de submenus na view
        $parent_id = $this->menu->find($id);

        // Chamando a view e enviando a ela a variável que contém os dados do menu selecionado
        return view('admin.menus.create', compact('parent_id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) { // Método que exibe formulário de edição do menu selecionado
        $user = $this->getAuthUser($request);
        if ($user->hasRole('administrador')) {
            $data['menu'] = Menu::where(array('id' => $id))->first();

            return view('admin.menus.edit', compact('data'));
        }

        return view("admin/errors/access_denied", array(
            'userAuth' => $user,
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\MenuRequest  $request
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(MenuRequest $request, Menu $menu) { // Atualizando dados do menu/submenu cadastrado
        // Recebendo dados do formulário
        $data = $request->all();
        unset($data['_token']);

        // Atualizando os dados
        $menu->update($data);

        // Criando uma session flash de mensagem de sucesso
        $request->session()->flash('message-success', 'Menu editado com sucesso');

        // Redirecionando para a página de listagem
        return redirect()->route('menus.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu) { // Método responsável por excluir o menu selecionado (caso seja menu RAIZ)
        //Verifica se é parent_id
        if ($menu->isFather())
            $this->deletaTudo($menu); // Se sim, chama método recursivo que exclui os submenus pertencentes a ele
        else
            $menu->delete(); // Se não, exclui o mesmo


            
// Cria uma session flash de mensagem de sucesso
        request()->session()->flash('message-success', 'Menu excluído com sucesso');

        // Redireciona para a listagem de menus
        return redirect()->route('menus.index');
    }

    /**
     * @param Menu $menu
     * @throws \Exception
     */
    public function deletaTudo(Menu $menu) { // Método que exclui menus e submenus
        // Recebe os elementos filho (caso exista)
        $menusFilhos = $menu->menus()->get();

        // Cria um laço passando por todos eles
        foreach ($menusFilhos as $filho) {
            if ($filho->isFather())
                $this->deletaTudo($filho); // Caso o elemento filho também possua submenus (ou seja, menu Pai), este método é chamado novamente
            else
                $filho->delete(); // Caso não tenha elementos filhos, exclui o menu
        }

        // Após terminar todas a iterações, sobrará somente o menu passado como parâmetro. Sendo assim, ele é excluído e retornamos ao método anterior
        $menu->delete();
        return;
    }

}
