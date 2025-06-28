<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
use App\Models\User;
use App\Models\Course;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $students = User::where('role_id', 3)->get();
        $paidCourses = Course::where('price', '>', 0)->get();

        foreach ($students->take(10) as $student) {
            $course = $paidCourses->random();
            
            Payment::factory()->create([
                'user_id' => $student->id,
                'course_id' => $course->id,
                'amount' => $course->price,
            ]);
        }
    }
}
