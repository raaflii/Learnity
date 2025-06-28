<?php

namespace Database\Factories;

use App\Models\CourseCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseCategoryFactory extends Factory
{
    protected $model = CourseCategory::class;

    public function definition()
    {
        $categories = [
            'Programming' => 'Learn various programming languages',
            'Design' => 'UI/UX and Graphic Design courses',
            'Business' => 'Business and entrepreneurship',
            'Marketing' => 'Digital marketing strategies',
            'Data Science' => 'Data analysis and machine learning',
            'Mobile Development' => 'iOS and Android development',
            'Web Development' => 'Frontend and backend development',
        ];

        $name = fake()->randomElement(array_keys($categories));
        
        return [
            'name' => $name,
            'description' => $categories[$name],
            'icon' => fake()->randomElement(['code', 'design', 'business', 'marketing', 'data', 'mobile', 'web']),
            'is_active' => fake()->boolean(95),
        ];
    }
}
