<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\User;
use App\Models\CourseCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition()
    {
        $titles = [
            'Complete Laravel Course',
            'React for Beginners',
            'UI/UX Design Masterclass',
            'Python Data Science',
            'Digital Marketing Strategy',
            'Flutter Mobile Development',
            'JavaScript Fundamentals',
            'Figma Design System',
        ];

        return [
            'title' => fake()->randomElement($titles),
            'description' => fake()->paragraphs(3, true),
            'short_description' => fake()->sentence(10),
            'thumbnail' => fake()->imageUrl(400, 300, 'education'),
            'price' => fake()->randomElement([0, 99000, 199000, 299000, 499000]),
            'level' => fake()->randomElement(['Beginner', 'Intermediate', 'Advanced']),
            'duration_hours' => fake()->numberBetween(5, 50),
            'instructor_id' => User::where('role_id', 2)->inRandomOrder()->first()->id ?? User::factory(),
            'category_id' => CourseCategory::inRandomOrder()->first()->id ?? CourseCategory::factory(),
            'is_published' => fake()->boolean(80),
            'is_featured' => fake()->boolean(20),
            'requirements' => fake()->paragraph(1),
            'what_you_learn' => fake()->paragraph(2),
        ];
    }
}
