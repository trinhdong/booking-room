<?php
namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpaceFactory extends Factory
{
    protected $model = \App\Models\Space::class;

    public function definition()
    {
        return [
            'room_id' => Room::factory(),
            'name' => $this->faker->word,
        ];
    }
}
