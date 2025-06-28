<?php

namespace Database\Seeders;

use App\Models\CourseReview;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CourseCategorySeeder::class,
            CourseSeeder::class,
            CourseContentSeeder::class,
            PaymentSeeder::class,
            EventSeeder::class,
        ]);
    }
}
