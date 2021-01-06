<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
  protected $fillable = [
      'parent_id', 'text', 'title', 'href', 'target', 'icon', 'published', 'order'
  ];

  // Relacionamento Many-to-Many entre os menus. (Isso facilita a vida de uma maneira sensacional!)
    public function menus()
    {
        return $this->hasMany(Menu::class,'parent_id','id');
    }

    // Função que verifica se o menu é filho (submenu) de outro
    public function isChild()
    {
        return !is_null($this->parent_id);
    }

    // Verifica e, caso seja um menu pai, retorna a quantidade de filhos
    public function isFather()
    {
        return parent::where('parent_id', $this->id)->get()->count();
    }

    // Retorna o name do elemento Pai do submenu selecionado/listado
    public function father()
    {
        return $this->where('id',$this->parent_id)->get(['name'])->first();
    }

    // Retorna o name de todos os filhos do menu selecionado
    public function childs()
    {
        return $this->where('parent_id',$this->id)->get(['name']);
    }

}
