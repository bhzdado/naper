<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesPermissionsTable extends Migration {

    public function up() {
        Schema::create('roles_permissions', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('permission_id');

            //FOREIGN KEY CONSTRAINTS
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');

            //SETTING THE PRIMARY KEYS
            //$table->primary(['id']);
        });
    }

    public function down() {
        Schema::dropIfExists('roles_permissions');
    }

}
