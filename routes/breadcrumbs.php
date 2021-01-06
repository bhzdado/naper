<?php
use App\User;
use App\Role;
use App\Permission;
use App\Company;

Breadcrumbs::for('admin', function ($trail) {
     $trail->push('Home', route('admin'));
});

/****************** ROLE *****************************************/
Breadcrumbs::for('web.role', function ($trail) {
    //$trail->parent('admin');
    $trail->push('Perfis', 'permissions/role');
}); 

Breadcrumbs::for('web.role.show', function ($trail, $id) {
    $role = Role::findOrFail($id);
    $trail->parent('web.role');
    $trail->push($role->name, 'Route::permissions/role');
});

Breadcrumbs::for('web.role.edit', function ($trail, $id) {
    $role = Role::findOrFail($id);
    $trail->parent('web.role');
    $trail->push("Edição de Perfil: " .$role->name, 'Route::permissions/role');
});

Breadcrumbs::for('web.role.create', function ($trail) {
    $trail->parent('web.role');
    $trail->push('Novo Perfil', 'Route::permissions/role');
});

/*****************************************************************/
/*****************************************************************/
/****************** PERMISSION *****************************************/

Breadcrumbs::for('web.permission', function ($trail) {
    //$trail->parent('admin');
    $trail->push('Permissões', 'permissions/permission');
});

Breadcrumbs::for('web.permission.create', function ($trail) {
    $trail->parent('web.permission');
    $trail->push('Nova Permissão', 'Route::permissions/permission');
});

Breadcrumbs::for('web.permission.edit', function ($trail, $id) {
    $permission = Permission::findOrFail($id);
    $trail->parent('web.permission');
    $trail->push("Edição de permissão: " .$permission->name, 'Route::permissions/permission');
});

Breadcrumbs::for('web.permission.show', function ($trail, $id) {
    $permission = Permission::findOrFail($id);
    $trail->parent('web.permission');
    $trail->push($permission->name, 'Route::permissions/permission');
});


/*****************************************************************/
/*****************************************************************/
/****************** USER *****************************************/

        
Breadcrumbs::for('web.user.openRoute', function ($trail) {
    $trail->push('Usuários', 'user');
});

Breadcrumbs::for('web.user', function ($trail) {
    $trail->push('Usuários', 'user');
});

Breadcrumbs::for('web.user.show', function ($trail, $id) {
    $user = User::findOrFail($id);
    $trail->parent('web.user');
    $trail->push($user->name, 'Route::user');
});

Breadcrumbs::for('web.user.edit', function ($trail, $id) {
    $user = User::findOrFail($id);
    $trail->parent('web.user');
    $trail->push("Edição de Usuário: " .$user->name, 'Route::user');
});

Breadcrumbs::for('web.user.create', function ($trail) {
    $trail->parent('web.user');
    $trail->push('Novo Usuário', 'Route::user');
});

Breadcrumbs::for('web.user.profile', function ($trail, $id) {
    $user = User::findOrFail($id);
    $trail->parent('web.user');
    $trail->push("Meu perfil", 'Route::user');
});

/*****************************************************************/
/*****************************************************************/
/****************** COMPANY *****************************************/

Breadcrumbs::for('web.company', function ($trail) {
    $trail->push('Empresas', 'company');
});

Breadcrumbs::for('web.company.show', function ($trail, $id) {
    $company = Company::findOrFail($id);
    $trail->parent('web.company');
    $trail->push($company->company_name, 'Route::company');
});

Breadcrumbs::for('web.company.edit', function ($trail, $id) {
    $company = Company::findOrFail($id);
    $trail->parent('web.company');
    $trail->push("Edição de Empresa: " .$company->name, 'Route::company');
});

Breadcrumbs::for('web.company.create', function ($trail) {
    $trail->parent('web.company');
    $trail->push('Nova Empresa', 'Route::company');
});

/*****************************************************************/
/*****************************************************************/
/****************** REPORT *****************************************/

Breadcrumbs::for('web.charts', function ($trail) {
    $trail->push('Relatórios', 'Route::reports');
});

Breadcrumbs::for('web.menu.index', function ($trail) {
    $trail->push('Administração de Menu');
});

Breadcrumbs::for('web.menu.create', function ($trail) {
  $trail->parent('web.menu.index');
  $trail->push('Cadastro de Menu', 'Route::menu');
});


Breadcrumbs::for('web.menu.edit', function ($trail) {
  $trail->parent('web.menu.index');
  $trail->push('Edição de Menu', 'Route::menu');
});

/*****************************************************************/
/*****************************************************************/
/****************** TRIBUTES *****************************************/

Breadcrumbs::for('web.tribute', function ($trail) {
    $trail->push('Impostos', 'Route::tribute');
});

Breadcrumbs::for('web.tribute.show', function ($trail, $id) {
    $tribute = \App\Tribute::findOrFail($id);
    $trail->parent('web.tribute');
    $trail->push($tribute->name, 'Route::tribute');
});

Breadcrumbs::for('web.tribute.edit', function ($trail, $id) {
    $tribute = \App\Tribute::findOrFail($id);
    $trail->parent('web.tribute');
    $trail->push("Edição de Imposto: " .$tribute->name, 'Route::tribute');
});

Breadcrumbs::for('web.tribute.create', function ($trail) {
    $trail->parent('web.tribute');
    $trail->push('Novo Imposto', 'Route::tribute');
});

/*****************************************************************/
/*****************************************************************/
/****************** MODULES *****************************************/

Breadcrumbs::for('web.module', function ($trail) {
    $trail->push('Módulos', 'Route::module');
});

Breadcrumbs::for('web.module.show', function ($trail, $id) {
    $module = \App\Module::findOrFail($id);
    $trail->parent('web.module');
    $trail->push($module->name, 'Route::module');
});

Breadcrumbs::for('web.module.edit', function ($trail, $id) {
    $module = \App\Module::findOrFail($id);
    $trail->parent('web.module');
    $trail->push("Edição de módulos: " .$module->name, 'Route::module');
});

Breadcrumbs::for('web.module.create', function ($trail) {
    $trail->parent('web.module');
    $trail->push('Novo Módulo', 'Route::question');
});

/*****************************************************************/
/*****************************************************************/
/****************** QUESTION *****************************************/

Breadcrumbs::for('web.avaliacao.question', function ($trail) {
    $trail->push('Questões', 'Route::question');
});

Breadcrumbs::for('web.avaliacao.question.show', function ($trail, $id) {
    $question = \App\Question::findOrFail($id);
    $trail->parent('web.avaliacao.question');
    $trail->push($question->question, 'Route::question');
});

Breadcrumbs::for('web.avaliacao.question.edit', function ($trail, $id) {
    $question = \App\Question::findOrFail($id);
    $trail->parent('web.avaliacao.question');
    $trail->push("Edição de questões: " .$question->question, 'Route::question');
});

Breadcrumbs::for('web.avaliacao.question.create', function ($trail) {
    $trail->parent('web.avaliacao.question');
    $trail->push('Nova Questão', 'Route::question');
});

/*****************************************************************/
/*****************************************************************/
/****************** EXAM *****************************************/

Breadcrumbs::for('web.avaliacao.exam', function ($trail) {
    $trail->push('Avaliação', 'Route::exam');
});

Breadcrumbs::for('web.avaliacao.exam.show', function ($trail, $id) {
    $exam = \App\Exam::findOrFail($id);
    $trail->parent('web.avaliacao.exam');
    $trail->push($exam->exam, 'Route::exam');
});

Breadcrumbs::for('web.avaliacao.exam.edit', function ($trail, $id) {
    $exam = \App\Exam::findOrFail($id);
    $trail->parent('web.avaliacao.exam');
    $trail->push("Edição de questões: " .$exam->exam, 'Route::exam');
});

Breadcrumbs::for('web.avaliacao.exam.create', function ($trail) {
    $trail->parent('web.avaliacao.exam');
    $trail->push('Nova Questão', 'Route::exam');
});

Breadcrumbs::for('web.avaliacao.questionGroup', function ($trail) {
    $trail->push('Assunto', 'Route::questionGroup');
});

Breadcrumbs::for('web.avaliacao.questionGroup.create', function ($trail) {
    $trail->parent('web.avaliacao.questionGroup');
    $trail->push('Novo Assunto', 'Route::questionGroup');
});