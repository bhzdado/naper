<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('group_id')->nullable();
            $table->text('question');
            $table->unsignedInteger('answer_id')->nullable();
            $table->string('weight', 3)->default('1');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('group_id')->references('id')->on('question_groups')->onDelete('set null');
        });

        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('question_id')->nullable();
            $table->text('option');
            $table->timestamps();
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->foreign('answer_id')->references('id')->on('answers')->onDelete('set null');
        });
        
        Schema::table('answers', function (Blueprint $table) {
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('answers');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

}
