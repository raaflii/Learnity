<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->text('question_text');
            $table->enum('question_type', ['multiple_choice', 'true_false', 'essay']);
            $table->decimal('points', 5, 2)->default(1);
            $table->integer('order_index');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('quiz_questions');
    }
}