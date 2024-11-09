<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Definir el estado por defecto del modelo.
     *
     * @return array
     */
    public function definition()
    {
        $firstName = $this->faker->regexify('[A-Z]{5}');
        $lastName = $this->faker->regexify('[A-Z]{5}');

        return [
            'first_name' => $firstName,
            'middle_name' => null, // Puede ser nulo
            'last_name' => $lastName,
            'second_last_name' => $this->faker->regexify('[A-Z]{5}'),
            'country' => $this->faker->randomElement(['Colombia', 'Estados Unidos']),
            'identification_type' => $this->faker->randomElement(['Cédula de Ciudadanía', 'Cédula de Extranjería', 'Pasaporte', 'Permiso Especial']),
            'identification_number' => Str::random(10),
            'hire_date' => $this->faker->date(),
            'area' => $this->faker->randomElement(['Administración', 'Financiera', 'Compras', 'Infraestructura', 'Operación', 'Talento Humano', 'Servicios Varios']),
            'status' => 'Activo',
            'email' => strtolower($firstName) . '.' . strtolower($lastName) . '@global.com.co',
        ];
    }
}
