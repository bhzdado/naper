<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

Route::get('/', function () {
    return view('welcomess');
});

Route::get('storage/{filename}', function ($filename) {
    $path = storage_path('app/public/' . str_replace("-", "/", $filename));

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::get('lang/dataTable/{filename}', function ($filename) {
    include_once "../public/back-assets/plugins/data-tables/$filename.php";
});

//Route::get("/admin", "ADMIN\HomeController@index")->name("admin");
Route::get('login', 'ADMIN\AuthController@login')->name('web.login');
Route::post('authenticate', 'ADMIN\AuthController@authenticate')->name('web.authenticate');
Route::get("verifyEmail/{code}", "ADMIN\AuthController@verifyEmail")->name("verify-email");
Route::get("resetPassword/{code}", "ADMIN\AuthController@resetPassword")->name("reset-password");

Route::get("admin/error/nomobile", "ADMIN\ErrorController@nomobile")->name("nomobile");

Route::get('/mailable', function () {
    $user = App\User::find(1);
    $user->activation_code = md5($user->email . time());

    $user->save();

    return new App\Mail\SendMail($user, "forgot-password");
});


Route::get('chart-js', 'ADMIN\ChartController@index');


//Route::middleware(['ResponseMiddleware'])->group(function() {
Route::group(['prefix' => 'admin', 'middleware' => ['ResponseMiddleware']], function () {
    Route::get("", "ADMIN\HomeController@index")->name("admin");
    Route::post("dashboard", "ADMIN\HomeController@dashboard")->name("web.dashboard");

    Route::post('user', 'ADMIN\UserController@openGrid')->name('web.user');
    Route::get('user/openGrid', 'ADMIN\UserController@openGrid')->name('web.user.openGrid');
    Route::post('user/create', 'ADMIN\UserController@create')->name('web.user.create');
    Route::post('user/edit/{id}', 'ADMIN\UserController@edit')->name('web.user.edit');
    Route::post('user/show/{id}', 'ADMIN\UserController@show')->name('web.user.show');
    Route::post('user/profile/{id}', 'ADMIN\UserController@profile')->name('web.user.profile');

    Route::post('role', 'ADMIN\RoleController@openGrid')->name('web.role');
    Route::get('role/openGrid', 'ADMIN\RoleController@openGrid')->name('web.role.openGrid');
    Route::post('role/create', 'ADMIN\RoleController@create')->name('web.role.create');
    Route::post('role/edit/{id}', 'ADMIN\RoleController@edit')->name('web.role.edit');
    Route::post('role/show/{id}', 'ADMIN\RoleController@show')->name('web.role.show');

    Route::post('permission', 'ADMIN\PermissionController@openGrid')->name('web.permission');
    Route::get('permission/openGrid', 'ADMIN\PermissionController@openGrid')->name('web.permission.openGrid');
    Route::post('permission/create', 'ADMIN\PermissionController@create')->name('web.permission.create');
    Route::post('permission/edit/{id}', 'ADMIN\PermissionController@edit')->name('web.permission.edit');
    Route::post('permission/show/{id}', 'ADMIN\PermissionController@show')->name('web.permission.show');

    Route::post('company', 'ADMIN\CompanyController@openGrid')->name('web.company');
    Route::get('company/openGrid', 'ADMIN\CompanyController@openGrid')->name('web.company.openGrid');
    Route::post('company/create', 'ADMIN\CompanyController@create')->name('web.company.create');
    Route::post('company/edit/{id}', 'ADMIN\CompanyController@edit')->name('web.company.edit');
    Route::post('company/show/{id}', 'ADMIN\CompanyController@show')->name('web.company.show');

    //Route::post('charts', 'ADMIN\ReportController@index')->name('web.reports');
    Route::post('charts/show/{chart}', 'ADMIN\ChartController@show')->name('web.charts');

    Route::get('home/menu', 'ADMIN\HomeController@menu')->name('web.menu');
    Route::post('menu', 'ADMIN\MenuController@index')->name('web.menu.index');
    Route::post('menu/create', 'ADMIN\MenuController@create')->name('web.menu.create');
    Route::post('menu/store/{id}', 'ADMIN\MenuController@store')->name('web.menu.store');
    Route::post('menu/show/{id}', 'ADMIN\MenuController@show')->name('web.menu.show');
    Route::post('menu/edit/{id}', 'ADMIN\MenuController@edit')->name('web.menu.edit');

    Route::put('menu/update', ['as' => 'update', 'uses' => 'ADMIN\MenuController@update']);
    Route::delete('menu/destroy', ['as' => 'destroy', 'uses' => 'ADMIN\MenuController@destroy']);

    Route::post('tribute', 'ADMIN\TributeController@openGrid')->name('web.tribute');
    Route::post('tribute/create', 'ADMIN\TributeController@create')->name('web.tribute.create');
    Route::post('tribute/store/{id}', 'ADMIN\TributeController@store')->name('web.tribute.store');
    Route::post('tribute/show/{id}', 'ADMIN\TributeController@show')->name('web.tribute.show');
    Route::post('tribute/edit/{id}', 'ADMIN\TributeController@edit')->name('web.tribute.edit');

    Route::post('module', 'ADMIN\ModuleController@openGrid')->name('web.module');
    Route::post('module/create', 'ADMIN\ModuleController@create')->name('web.module.create');
    Route::post('module/store/{id}', 'ADMIN\ModuleController@store')->name('web.module.store');
    Route::post('module/show/{id}', 'ADMIN\ModuleController@show')->name('web.module.show');
    Route::post('module/edit/{id}', 'ADMIN\ModuleController@edit')->name('web.module.edit');
    
    Route::post('avaliacao', 'ADMIN\AvaliacaoController@index')->name('web.avaliacao');
    Route::post('avaliacao/question', 'ADMIN\QuestionController@openGrid')->name('web.avaliacao.question');
    Route::post('avaliacao/question/create', 'ADMIN\QuestionController@create')->name('web.avaliacao.question.create');
    Route::post('avaliacao/question/edit/{id}', 'ADMIN\QuestionController@edit')->name('web.avaliacao.question.edit');
    Route::post('avaliacao/question/show/{id}', 'ADMIN\QuestionController@show')->name('web.avaliacao.question.show');
    
    Route::post('avaliacao/exam', 'ADMIN\ExamController@openGrid')->name('web.avaliacao.exam');
    Route::post('avaliacao/exam/create', 'ADMIN\ExamController@create')->name('web.avaliacao.exam.create');
    
    Route::post('avaliacao/questionGroup', 'ADMIN\QuestionGroupController@openGrid')->name('web.avaliacao.questionGroup');
    Route::post('avaliacao/questionGroup/create', 'ADMIN\QuestionGroupController@create')->name('web.avaliacao.questionGroup.create');
});


Route::group(['prefix' => 'avaliacao', 'middleware' => ['ResponseMiddleware']], function () {
    Route::get("", "avaliacao\HomeController@index")->name("admin");
});