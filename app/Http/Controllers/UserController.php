<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Exception;

class UserController extends Controller
{
    /**
     * Crea un nuevo usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Validación de los datos recibidos
            $validatedData = $request->validate([
                'first_name' => 'required|regex:/^[A-Z]+$/|max:20',
                'middle_name' => 'nullable|regex:/^[A-Z\s]+$/|max:50',
                'last_name' => 'required|regex:/^[A-Z ]+$/|max:20',
                'second_last_name' => 'required|regex:/^[A-Z]+$/|max:20',
                'country' => 'required|in:Colombia,Estados Unidos',
                'identification_type' => 'required|in:Cédula de Ciudadanía,Cédula de Extranjería,Pasaporte,Permiso Especial',
                'identification_number' => 'required|alpha_num|max:20',
                'hire_date' => 'required|date',
                'area' => 'required|in:Administración,Financiera,Compras,Infraestructura,Operación,Talento Humano,Servicios Varios',
                'status' => 'required|in:Activo',
            ]);

            // Generar el correo electrónico automáticamente
            $emailDomain = $validatedData['country'] === 'Colombia' ? 'global.com.co' : 'global.com.us';
            $baseEmail = strtolower($validatedData['first_name']) . '.' . strtolower(str_replace(' ', '', $validatedData['last_name']));
            $email = $this->generateUniqueEmail($baseEmail, $emailDomain);

            // Agregar el correo electrónico generado a los datos validados
            $validatedData['email'] = $email;

            // Crear el usuario en la base de datos
            $user = User::create($validatedData);

            // Retornar respuesta indicando que el usuario fue creado con éxito
            return response()->json(['message' => 'Usuario creado con éxito', 'user' => $user], 201);

        } catch (ValidationException $e) {
            // Si la validación falla, retornar un error con el detalle de la validación
            return response()->json(['message' => 'Datos de entrada inválidos', 'errors' => $e->errors()], 422);
        } catch (Exception $e) {
            // Captura cualquier otra excepción
            return response()->json(['message' => 'Ocurrió un error al crear el usuario', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Actualizar un usuario existente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        try {
            // Validación de los datos recibidos
            $validatedData = $request->validate([
                'first_name' => 'required|regex:/^[A-Z]+$/|max:20',
                'middle_name' => 'nullable|regex:/^[A-Z\s ]+$/|max:50',
                'last_name' => 'required|regex:/^[A-Z ]+$/|max:20',
                'second_last_name' => 'required|regex:/^[A-Z]+$/|max:20',
                'country' => 'required|in:Colombia,Estados Unidos',
                'identification_type' => 'required|in:Cédula de Ciudadanía,Cédula de Extranjería,Pasaporte,Permiso Especial',
                'identification_number' => 'required|alpha_num|max:20,' . $user->id,
                'hire_date' => 'required|date|before_or_equal:today',
                'area' => 'required|in:Administración,Financiera,Compras,Infraestructura,Operación,Talento Humano,Servicios Varios',
                'status' => 'required|in:Activo',
            ]);

            // Generar el correo electrónico automáticamente
            $emailDomain = $validatedData['country'] === 'Colombia' ? 'global.com.co' : 'global.com.us';
            $baseEmail = strtolower($validatedData['first_name']) . '.' . strtolower(str_replace(' ', '', $validatedData['last_name']));
            $email = $this->generateUniqueEmail($baseEmail, $emailDomain, $user->id);

            // Agregar el correo electrónico generado a los datos validados
            $validatedData['email'] = $email;

            // Actualizar el usuario en la base de datos
            $user->update($validatedData);
            
            // Retornar respuesta indicando que el usuario fue actualizado con éxito
            return response()->json(['message' => 'Usuario actualizado con éxito', 'user' => $user], 200);

        } catch (ModelNotFoundException $e) {
            // Si no se encuentra el usuario, se maneja esta excepción
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        } catch (ValidationException $e) {
            // Si la validación falla, retornar un error con el detalle de la validación
            return response()->json(['message' => 'Datos de entrada inválidos', 'errors' => $e->errors()], 422);
        } catch (Exception $e) {
            // Captura cualquier otra excepción
            return response()->json(['message' => 'Ocurrió un error al actualizar el usuario', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Eliminar un usuario específico.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            // Eliminar el usuario de la base de datos
            $user->delete();

            // Retornar respuesta indicando que el usuario fue eliminado con éxito
            return response()->json(['message' => 'Usuario eliminado con éxito'], 200);

        } catch (Exception $e) {
            // Captura cualquier excepción durante la eliminación
            return response()->json(['message' => 'Ocurrió un error al eliminar el usuario', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Mostrar un usuario específico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            // Buscar el usuario por ID
            $user = User::findOrFail($id);

            // Si el usuario existe, retornar los datos del usuario
            return response()->json($user);

        } catch (ModelNotFoundException $e) {
            // Si no se encuentra el usuario, manejar la excepción
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        } catch (Exception $e) {
            // Captura cualquier otra excepción
            return response()->json(['message' => 'Ocurrió un error al obtener el usuario', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Mostrar la lista de usuarios.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $users = User::all();

            // Retornar la lista de usuarios
            return response()->json($users);

        } catch (Exception $e) {
            // Captura cualquier excepción durante la obtención de los usuarios
            return response()->json(['message' => 'Ocurrió un error al obtener la lista de usuarios', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Generar un correo electrónico único.
     *
     * @param  string  $baseEmail
     * @param  string  $domain
     * @return string
     */
    private function generateUniqueEmail($baseEmail, $domain, $userId = null)
    {
        $email = $baseEmail . '@' . $domain;
        $counter = 1;

        // Verificar si el correo ya existe y generar un nuevo correo si es necesario
        while (User::where('email', $email)->where('id', '<>', $userId)->exists()) {
            $email = $baseEmail . '.' . $counter . '@' . $domain;
            $counter++;
        }

        return $email;
    }
}
