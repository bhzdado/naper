<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('role_id');
            $table->string('name');
            $table->unsignedInteger('company_id')->unsigned()->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            
            $table->date('date_birth')->nullable();
            
            $table->string('cpf', 11)->nullable();
            $table->string('cep', 8)->nullable();
            $table->string('address')->nullable();
            $table->string('number', 10)->nullable();
            $table->string('complement', 100)->nullable();
            $table->string('neighborhood')->nullable();
            
            $table->unsignedInteger('city_id')->unsigned()->nullable();
            $table->unsignedInteger('state_id')->unsigned()->nullable();

            $table->string('telephone', 11)->nullable();
            $table->string('cellphone', 11)->nullable();
            $table->string('avatar')->default('default.png');
            $table->string('active', 2)->default('0');
            $table->string('activation_code')->default('');
            
            $table->rememberToken();
            $table->timestamps();
            
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
