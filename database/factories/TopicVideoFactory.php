<?php

namespace Database\Factories;

use App\Models\TopicVideo;
use App\Models\CourseTopic;
use Illuminate\Database\Eloquent\Factories\Factory;

class TopicVideoFactory extends Factory
{
    protected $model = TopicVideo::class;

    public function definition()
    {
        $videoType = fake()->randomElement(['youtube', 'local', 'vimeo']);
        
        $videoPath = match($videoType) {
            'youtube' => 'https://www.youtube.com/watch?v=' . fake()->lexify('???????????'),
            'vimeo' => 'https://vimeo.com/' . fake()->numberBetween(100000000, 999999999),
            'local' => 'videos/' . fake()->lexify('video_??????') . '.mp4',
        };

        return [
            'topic_id' => CourseTopic::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(1),
            'video_type' => $videoType,
            'video_path' => $videoPath,
            'duration_seconds' => fake()->numberBetween(300, 3600), // 5-60 minutes
            'order_index' => fake()->numberBetween(1, 15),
        ];
    }
}
