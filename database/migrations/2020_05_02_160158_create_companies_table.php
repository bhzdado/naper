<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->string('company_name');
            $table->string('fantasy_name')->nullable();
            $table->string('cnpj', 14);
            $table->string('email')->unique();
            $table->string('responsible');
            
            $table->string('cep', 8)->nullable();
            $table->string('address')->nullable();
            $table->string('number', 10)->nullable();
            $table->string('complement', 100)->nullable();
            $table->string('neighborhood')->nullable();
            
            $table->unsignedInteger('city_id')->unsigned()->nullable();
            $table->unsignedInteger('state_id')->unsigned()->nullable();
            
            $table->string('telephone', 11)->nullable();
            $table->string('state_registration', 20)->nullable();
            $table->string('municipal_registration', 20)->nullable();
            
            $table->string('active', 2)->default('1');
            $table->string('logo')->default('default.png');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
