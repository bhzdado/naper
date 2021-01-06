<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
  |--------------------------------------------------------------------------
  | API Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register API routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | is assigned the "api" middleware group. Enjoy building your API!
  |
  Route::middleware('auth:api')->get('/user', function (Request $request) {
  return $request->user();
  });
 *  */

Route::group(['prefix' => 'v1'], function () {
    Route::post('login', 'API\AuthController@login')->name('api.login');
    Route::post('register', 'API\AuthController@register')->name('api.register');
    Route::get('unauthenticated', 'API\AuthController@unauthenticated')->name('api.unauthenticated');
    Route::get('expiredToken', 'API\AuthController@expiredToken')->name('api.expiredToken');
    Route::post('changePassword', 'API\AuthController@changePassword')->name('api.changePassword');
    Route::post('changePasswordProfile', 'API\AuthController@changePasswordProfile')->name('api.changePasswordProfile');
    Route::get('forgotPassword/{email}', 'API\AuthController@forgotPassword')->name('api.forgotPassword');

    Route::middleware(['CheckToken', 'auth:api', 'ResponseMiddleware'])->group(function() {
        Route::get('logout', 'API\AuthController@logout')->name('api.logout');
        Route::get('detailsUser', 'API\AuthController@detailsUserAuth')->name('api.detailsUser');
        Route::get('validateToken', 'API\AuthController@validateToken')->name('api.validateToken');
        Route::get('permissions/iniciarPermissao', 'API\PermissionController@iniciarPermissao')->name('api.permissions.index');

        Route::get('city/searchCep', 'API\CityController@searchCep')->name('api.city.searchCep');

        Route::get('user/loadDataGrid', 'API\UserController@loadDataGrid')->name('api.user.loadDataGrid');
        Route::post('user/store', 'API\UserController@store')->name('api.user.store');
        Route::patch('user/update/{id}', 'API\UserController@update')->name('api.user.update');
        Route::get('user/show/{id}', 'API\UserController@show')->name('api.user.show');
        Route::delete('user/destroy/{id}', 'API\UserController@destroy')->name('api.user.destroy');
        Route::post('user/saveAvatar/{id}', 'API\UserController@saveAvatar')->name('api.user.saveAvatar');

        Route::get('user/userAccess', 'API\UserController@userAccess')->name('api.user.userAccess');

        Route::get('permission/loadDataGrid', 'API\PermissionController@loadDataGrid')->name('api.permission.loadDataGrid');
        Route::post('permission/store', 'API\PermissionController@store')->name('api.permission.store');
        Route::patch('permission/update/{id}', 'API\PermissionController@update')->name('api.permission.update');
        Route::get('permission/show/{id}', 'API\PermissionController@show')->name('api.permission.show');
        Route::delete('permission/destroy/{id}', 'API\PermissionController@destroy')->name('api.permission.destroy');

        Route::get('role/loadDataGrid', 'API\RoleController@loadDataGrid')->name('api.role.loadDataGrid');
        Route::post('role/store', 'API\RoleController@store')->name('api.role.store');
        Route::patch('role/update/{id}', 'API\RoleController@update')->name('api.role.update');
        Route::get('role/show/{id}', 'API\RoleController@show')->name('api.role.show');
        Route::delete('role/destroy/{id}', 'API\RoleController@destroy')->name('api.role.destroy');

        Route::get('company', 'API\CompanyController@index')->name('api.company');
        Route::get('company/loadDataGrid', 'API\CompanyController@loadDataGrid')->name('api.company.loadDataGrid');
        Route::post('company/store', 'API\CompanyController@store')->name('api.company.store');
        Route::patch('company/update/{id}', 'API\CompanyController@update')->name('api.company.update');
        Route::get('company/show/{id}', 'API\CompanyController@show')->name('api.company.show');
        Route::delete('company/destroy/{id}', 'API\CompanyController@destroy')->name('api.company.destroy');

        Route::get('menu', 'API\MenuController@index')->name('api.menu');
        Route::post('menu/datagrid', 'API\MenuController@dataGrid')->name('api.menu.datagrid');
        Route::post('menu/store', 'API\MenuController@store')->name('api.menu.store');

        Route::get('tribute/loadDataGrid', 'API\TributeController@loadDataGrid')->name('api.tribute.loadDataGrid');
        Route::post('tribute/store', 'API\TributeController@store')->name('api.tribute.store');
        Route::patch('tribute/update/{id}', 'API\TributeController@update')->name('api.tribute.update');
        Route::delete('tribute/destroy/{id}', 'API\TributeController@destroy')->name('api.tribute.destroy');

        Route::get('module/loadDataGrid', 'API\ModuleController@loadDataGrid')->name('api.module.loadDataGrid');
        Route::post('module/store', 'API\ModuleController@store')->name('api.module.store');
        Route::patch('module/update/{id}', 'API\ModuleController@update')->name('api.module.update');
        Route::delete('module/destroy/{id}', 'API\ModuleController@destroy')->name('api.module.destroy');
        
        Route::get('avaliacao/question/loadDataGrid', 'API\QuestionController@loadDataGrid')->name('api.avaliacao.question.loadDataGrid');
        Route::post('question/store', 'API\QuestionController@store')->name('api.question.store');
        Route::patch('question/update/{id}', 'API\QuestionController@update')->name('api.question.update');
        Route::delete('avaliacao/question/destroy/{id}', 'API\QuestionController@destroy')->name('api.avaliacao.question.destroy');
        
        Route::get('avaliacao/questionGroup/loadDataGrid', 'API\QuestionGroupController@loadDataGrid')->name('api.avaliacao.questionGroup.loadDataGrid');
        Route::post('questionGroup/store', 'API\QuestionGroupController@store')->name('api.questionGroup.store');
    });
});

/*
 * dataGrid
    Route::get('products', 'ProductsController@index');
    Route::post('products', 'ProductsController@store');
    Route::get('products/{product}', 'ProductsController@show');
    Route::get('products/{product}/edit', 'ProductsController@edit');
    Route::patch('products/{product}', 'ProductsController@update');
    Route::delete('products/{product}', 'ProductsController@destroy');
 *
 */
