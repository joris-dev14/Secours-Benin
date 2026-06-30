<?php

namespace Database\Factories;

use App\Models\Citoyen;
use Illuminate\Database\Eloquent\Factories\Factory;

class CitoyenFactory extends Factory
{
    protected $model = Citoyen::class;

    public function definition(): array
    {
        return [
            'telephone' => $this->faker->unique()->numerify('########'),
            'consentement' => true,
        ];
    }
}
