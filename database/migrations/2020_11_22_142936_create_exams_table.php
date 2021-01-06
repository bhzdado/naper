<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('exams', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->string('name');
            $table->string('value', 3)->default('0');
            $table->string('number_questions')->nullable();
            $table->string('maximum_time')->nullable();
            $table->date('start_time')->nullable();
            $table->date('finish_time')->nullable();
            $table->string('real_time_correction', 1)->default('0');
            $table->string('published', 2)->default('0');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('exams');
    }

}
