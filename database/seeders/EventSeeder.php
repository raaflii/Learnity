<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        if ($users->count() === 0) {
            $this->command->error('No users found. Please run UserSeeder first.');
            return;
        }

        $admin = User::whereHas('role', function($query) {
            $query->where('name', 'admin');
        })->first();

        if ($admin) {
            Event::factory(3)->create([
                'user_id' => $admin->id,
                'type' => 'meeting',
                'title' => 'Admin Meeting'
            ]);

            Event::factory(2)->create([
                'user_id' => $admin->id,
                'type' => 'appointment',
                'title' => 'System Review'
            ]);

            Event::factory()->today()->create([
                'user_id' => $admin->id,
                'title' => 'Daily Admin Check',
                'type' => 'task'
            ]);
        }

        $teachers = User::whereHas('role', function($query) {
            $query->where('name', 'teacher');
        })->get();

        foreach ($teachers as $teacher) {
            Event::factory(4)->create([
                'user_id' => $teacher->id,
            ]);

            Event::factory()->create([
                'user_id' => $teacher->id,
                'type' => 'meeting',
                'title' => 'Class Meeting - ' . fake()->sentence(2),
                'location' => 'Online Class'
            ]);

            Event::factory()->create([
                'user_id' => $teacher->id,
                'type' => 'task',
                'title' => 'Prepare Course Materials',
                'color' => '#10B981'
            ]);

            Event::factory()->today()->create([
                'user_id' => $teacher->id,
                'title' => 'Today Class Schedule',
                'type' => 'appointment'
            ]);
        }

        $students = User::whereHas('role', function($query) {
            $query->where('name', 'student');
        })->get();

        foreach ($students as $student) {
            Event::factory(fake()->numberBetween(3, 6))->create([
                'user_id' => $student->id,
            ]);

            Event::factory()->create([
                'user_id' => $student->id,
                'type' => 'task',
                'title' => 'Assignment Deadline - ' . fake()->sentence(2),
                'color' => '#F59E0B'
            ]);

            Event::factory()->create([
                'user_id' => $student->id,
                'type' => 'reminder',
                'title' => 'Study Session',
                'color' => '#8B5CF6'
            ]);

            if (fake()->boolean(70)) {
                Event::factory()->today()->create([
                    'user_id' => $student->id,
                    'title' => 'Study Time',
                    'type' => 'task'
                ]);
            }
        }
    }
}