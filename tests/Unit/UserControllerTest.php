<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test para crear un usuario.
     *
     * @return void
     */
    public function test_store_user()
    {
          // Datos válidos de prueba
          $data = [
            'first_name' => 'JUAN',
            'middle_name' => 'CARLOS',
            'last_name' => 'PEREZ',
            'second_last_name' => 'GOMEZ',
            'country' => 'Colombia',
            'identification_type' => 'Cédula de Ciudadanía',
            'identification_number' => '123456789',
            'hire_date' => '2022-01-01',
            'area' => 'Administración',
            'status' => 'Activo',
        ];

        // Simular la petición POST para crear un usuario
        $response = $this->postJson('/api/v1/users', $data);

        // Verificar que la respuesta sea correcta
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'user' => [
                'id',
                'first_name',
                'last_name',
                'email',
                'country',
                'created_at',
                'updated_at',
            ],
        ]);

        // Verificar que el usuario fue creado en la base de datos
        $this->assertDatabaseHas('users', [
            'first_name' => 'JUAN',
            'last_name' => 'PEREZ',
            'email' => 'juan.perez@global.com.co',
        ]);
    }

    /**
     * Test para actualizar un usuario.
     *
     * @return void
     */
    public function test_update_user()
    {
        $user = User::factory()->create();

        $updateData = [
            'first_name' => 'NUEVO',
            'middle_name' => null,
            'last_name' => 'NOMBRE',
            'second_last_name' => 'NUEVO',
            'country' => 'Estados Unidos',
            'identification_type' => 'Pasaporte',
            'identification_number' => '12345',
            'hire_date' => now()->toDateString(),
            'area' => 'Financiera',
            'status' => 'Activo',
        ];

        $response = $this->putJson("/api/v1/users/{$user->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Usuario actualizado con éxito']);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'NUEVO',
            'last_name' => 'NOMBRE',
        ]);
    }

    /**
     * Test para eliminar un usuario.
     *
     * @return void
     */
    public function test_destroy_user()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/v1/users/{$user->id}");

        $response->assertStatus(200)
                ->assertJson(['message' => 'Usuario eliminado con éxito']);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    /**
     * Test para mostrar un usuario específico.
     *
     * @return void
     */
    public function test_show_user()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/v1/users/{$user->id}");

        $response->assertStatus(200)
                 ->assertJson([
                    'first_name' => $user->first_name,
                    'email' => $user->email,
                 ]);
    }

    /**
     * Test para mostrar la lista de usuarios.
     *
     * @return void
     */
    public function test_index_users()
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }
}
