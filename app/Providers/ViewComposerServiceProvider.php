<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
      view()->composer(['layouts.master','admin.menus.*'], function($view){
          $view->with('menus', \App\Menu::where('parent_id','=',null)->get());
          $view->with('allMenus', \App\Menu::all());
      });
    }
}
