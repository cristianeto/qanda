<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Question::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description'=> $this->faker->sentence,
            'answer' => $this->faker->word,
//            'status' => $this->faker->randomElement(['NOT ANSWERED']),
            'status' => 'NOT ANSWERED',
            'user_id' => User::factory(),
        ];
    }
}
