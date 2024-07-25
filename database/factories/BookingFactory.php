<?php
namespace Database\Factories;

use App\Models\Space;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = \App\Models\Booking::class;

    public function definition()
    {
        $startTime = $this->faker->dateTimeBetween('now', '+1 month');
        $endTime = (clone $startTime)->modify('+3 hours');

        return [
            'space_id' => Space::factory(),
            'start_time' => $startTime,
            'end_time' => $endTime,
        ];
    }
}
