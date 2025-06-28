<?php

namespace Database\Factories;

use App\Models\UserProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfileFactory extends Factory
{
    protected $model = UserProfile::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'bio' => fake()->paragraph(3),
            'education' => fake()->randomElement([
                'S1 Teknik Informatika',
                'S2 Manajemen',
                'S1 Desain Grafis',
                'S3 Pendidikan',
                'Diploma Web Development'
            ]),
            'experience' => fake()->paragraph(2),
            'expertise' => fake()->randomElement([
                'Web Development, Laravel, React',
                'Data Science, Python, Machine Learning',
                'UI/UX Design, Figma, Adobe',
                'Digital Marketing, SEO, SEM',
                'Mobile Development, Flutter, React Native'
            ]),
            'social_links' => [
                'linkedin' => 'https://linkedin.com/in/' . fake()->userName(),
                'github' => 'https://github.com/' . fake()->userName(),
                'twitter' => 'https://twitter.com/' . fake()->userName(),
            ],
        ];
    }
}
