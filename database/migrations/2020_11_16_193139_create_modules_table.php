<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('tribute_id');
            $table->string('name'); 
            $table->string('label'); 
            $table->string('published', 2)->default('0');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('tribute_id')->references('id')->on('tributes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
