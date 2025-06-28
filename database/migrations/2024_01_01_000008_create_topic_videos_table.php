<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicVideosTable extends Migration
{
    public function up()
    {
        Schema::create('topic_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('course_topics')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('video_type', ['youtube', 'local', 'vimeo']);
            $table->string('video_path', 500);
            $table->integer('duration_seconds')->default(0);
            $table->integer('order_index');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('topic_videos');
    }
}