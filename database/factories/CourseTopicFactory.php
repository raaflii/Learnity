<?php

namespace Database\Factories;

use App\Models\CourseTopic;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseTopicFactory extends Factory
{
    protected $model = CourseTopic::class;

    public function definition()
    {
        return [
            'course_id' => Course::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(1),
            'order_index' => fake()->numberBetween(1, 10),
            'is_published' => fake()->boolean(85),
        ];
    }
}
