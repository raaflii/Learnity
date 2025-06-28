<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseCategory;

class CourseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            ['name' => 'Programming', 'description' => 'Learn various programming languages', 'icon' => 'code'],
            ['name' => 'Design', 'description' => 'UI/UX and Graphic Design courses', 'icon' => 'design'],
            ['name' => 'Business', 'description' => 'Business and entrepreneurship', 'icon' => 'business'],
            ['name' => 'Marketing', 'description' => 'Digital marketing strategies', 'icon' => 'marketing'],
            ['name' => 'Data Science', 'description' => 'Data analysis and machine learning', 'icon' => 'data'],
        ];

        foreach ($categories as $category) {
            CourseCategory::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
