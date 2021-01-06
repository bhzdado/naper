<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExameQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exame_questions', function (Blueprint $table) {
            $table->increments('id')->unsignedInteger();
            $table->unsignedInteger('exam_id');
            $table->unsignedInteger('question_id');
            $table->string('weight', 3)->default('1');
            $table->string('order', 5)->default('0');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exame_questions');
    }
}
