<?php

namespace Database\Factories;

use App\Client;
use App\Journal;
use Illuminate\Database\Eloquent\Factories\Factory;

class JournalFactory extends Factory
{

    protected $model = Journal::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'client_id' => factory(Client::class)->create()->id,
            'entry_date' => $this->faker->date(),
            'content' => $this->faker->paragraphs(asText: true),
        ];
    }
}
