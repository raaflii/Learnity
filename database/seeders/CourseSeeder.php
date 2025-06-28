<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;
use App\Models\CourseCategory;
use App\Models\CourseEnrollment;
use App\Models\CourseReview;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $teachers = User::where('role_id', 2)->get();
        $categories = CourseCategory::all();
        $students = User::where('role_id', 3)->get();

        $courses = Course::factory(15)->create([
            'instructor_id' => $teachers->random()->id,
            'category_id' => $categories->random()->id,
        ]);

        foreach ($courses as $course) {
        $enrolledStudents = $students->random(rand(3, 8));

            foreach ($enrolledStudents as $student) {
                $enrollment = CourseEnrollment::create([
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                    'progress_percentage' => rand(0, 100),
                    'enrolled_at' => fake()->dateTimeBetween('-3 months', 'now'),
                    'last_accessed_at' => fake()->dateTimeBetween('-1 week', 'now'),
                ]);

                // Buat review jika progress > 50%
                if ($enrollment->progress_percentage >= 50) {
                    CourseReview::create([
                        'user_id' => $student->id,
                        'course_id' => $course->id,
                        'enrollment_id' => $enrollment->id,
                        'rating' => rand(3, 5),
                        'review_text' => fake()->optional()->sentence(12),
                        'is_approved' => true,
                    ]);
                }
            }

            // Hitung ulang rating & review count
            $totalReviews = $course->reviews()->count();
            $averageRating = $course->reviews()->avg('rating') ?? 0;

            $course->update([
                'average_rating' => round($averageRating, 2),
                'total_reviews' => $totalReviews,
            ]);
        }
        
    }
}
