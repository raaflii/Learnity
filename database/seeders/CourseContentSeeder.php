<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\CourseTopic;
use App\Models\TopicVideo;

class CourseContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $courses = Course::all();

        foreach ($courses as $course) {
            $topics = CourseTopic::factory(rand(3, 6))->create([
                'course_id' => $course->id,
            ]);

            foreach ($topics as $index => $topic) {
                $topic->update(['order_index' => $index + 1]);

                $videos = TopicVideo::factory(rand(2, 5))->create([
                    'topic_id' => $topic->id,
                ]);

                foreach ($videos as $videoIndex => $video) {
                    $video->update(['order_index' => $videoIndex + 1]);
                }
            }
        }
    }
}