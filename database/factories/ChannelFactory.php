<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChannelFactory extends Factory
{

    protected $model = Channel::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(3),
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'description' => $this->faker->sentence(30)
        ];
    }
}
