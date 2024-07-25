<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = \App\Models\Room::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
