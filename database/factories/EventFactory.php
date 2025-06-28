<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        $startTime = fake()->dateTimeBetween('now', '+30 days');
        $endTime = Carbon::parse($startTime)->addHours(fake()->numberBetween(1, 4));

        $types = ['meeting', 'task', 'reminder', 'appointment', 'other'];
        $statuses = ['scheduled', 'completed', 'cancelled'];
        $colors = ['#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899'];

        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'location' => fake()->optional()->address(),
            'type' => fake()->randomElement($types),
            'color' => fake()->randomElement($colors),
            'all_day' => fake()->boolean(20), // 20% chance all day
            'status' => fake()->randomElement($statuses),
        ];
    }

    // State untuk event yang sedang berlangsung hari ini
    public function today()
    {
        return $this->state(function (array $attributes) {
            $startTime = Carbon::today()->addHours(fake()->numberBetween(8, 18));
            $endTime = $startTime->copy()->addHours(fake()->numberBetween(1, 3));

            return [
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => 'scheduled',
            ];
        });
    }

    // State untuk event meeting
    public function meeting()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'meeting',
                'title' => 'Meeting: ' . fake()->sentence(2),
                'location' => fake()->randomElement([
                    'Zoom Meeting',
                    'Google Meet',
                    'Conference Room A',
                    'Online'
                ]),
            ];
        });
    }

    // State untuk task/tugas
    public function task()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'task',
                'title' => 'Task: ' . fake()->sentence(2),
                'color' => '#F59E0B',
            ];
        });
    }
}