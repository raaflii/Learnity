<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        $status = fake()->randomElement(['pending', 'settlement', 'cancel', 'expire', 'failure']);
        
        return [
            'user_id' => User::factory(),
            'course_id' => Course::factory(),
            'order_id' => 'ORDER-' . fake()->unique()->numerify('########'),
            'amount' => fake()->randomElement([99000, 199000, 299000, 499000]),
            'payment_status' => $status,
            'payment_type' => fake()->randomElement(['credit_card', 'bank_transfer', 'gopay', 'ovo', 'dana']),
            'transaction_id' => $status === 'settlement' ? fake()->uuid() : null,
            'transaction_status' => $status,
            'transaction_time' => $status !== 'pending' ? fake()->dateTimeBetween('-1 month', 'now') : null,
            'settlement_time' => $status === 'settlement' ? fake()->dateTimeBetween('-1 month', 'now') : null,
            'midtrans_response' => [
                'status_code' => $status === 'settlement' ? '200' : '201',
                'status_message' => $status === 'settlement' ? 'Success' : 'Pending',
            ],
        ];
    }
}
