<?php

namespace Database\Factories;

use App\Models\CourseReview;
use App\Models\Course;
use App\Models\User;
use App\Models\CourseEnrollment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseReviewFactory extends Factory
{
    protected $model = CourseReview::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'course_id' => Course::factory(),
            'enrollment_id' => CourseEnrollment::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'review_text' => $this->faker->optional()->sentence(10),
            'is_approved' => $this->faker->boolean(90),
        ];
    }
}
