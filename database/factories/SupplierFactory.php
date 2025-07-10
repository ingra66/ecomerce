<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nick' => $this->faker->unique()->userName(),
            'nombre' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            'telefono' => $this->faker->phoneNumber(),
            'direccion' => $this->faker->address(),
            'web' => $this->faker->url(),
            'notas' => $this->faker->optional()->sentence(),
        ];
    }
} 