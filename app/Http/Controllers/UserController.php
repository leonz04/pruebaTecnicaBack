<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
        
        // Validación de los datos recibidos
        $validatedData = $request->validate([
            'first_name' => 'required|regex:/^[A-Z]+$/|max:20',
            'middle_name' => 'nullable|regex:/^[A-Z\s]+$/|max:50',
            'last_name' => 'required|regex:/^[A-Z]+$/|max:20',
            'second_last_name' => 'required|regex:/^[A-Z]+$/|max:20',
            'country' => 'required|in:Colombia,Estados Unidos',
            'identification_type' => 'required|in:Cédula de Ciudadanía,Cédula de Extranjería,Pasaporte,Permiso Especial',
            'identification_number' => 'required|alpha_num|max:20|unique:users,identification_number',
            'hire_date' => 'required|date',
            'area' => 'required|in:Administración,Financiera,Compras,Infraestructura,Operación,Talento Humano,Servicios Varios',
            'status' => 'required|in:Activo',
        ]);

        // Generar el correo electrónico automáticamente
        $emailDomain = $validatedData['country'] === 'Colombia' ? 'global.com.co' : 'global.com.us';
        $baseEmail = strtolower($validatedData['first_name']) . '.' . strtolower($validatedData['last_name']);
        $email = $this->generateUniqueEmail($baseEmail, $emailDomain);

        // Agregar el correo electrónico generado a los datos validados
        $validatedData['email'] = $email;

        // Crear el usuario en la base de datos si la validación pasa
        $user = User::create($validatedData);

        // Retornar respuesta indicando que el usuario fue creado con éxito
        return response()->json(['message' => 'Usuario creado con éxito', 'user' => $user], 201);
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
        // Validación de los datos recibidos
        $validatedData = $request->validate([
            'first_name' => 'required|regex:/^[A-Z]+$/|max:20',
            'middle_name' => 'nullable|regex:/^[A-Z\s]+$/|max:50',
            'last_name' => 'required|regex:/^[A-Z]+$/|max:20',
            'second_last_name' => 'required|regex:/^[A-Z]+$/|max:20',
            'country' => 'required|in:Colombia,Estados Unidos',
            'identification_type' => 'required|in:Cédula de Ciudadanía,Cédula de Extranjería,Pasaporte,Permiso Especial',
            'identification_number' => 'required|alpha_num|max:20|unique:users,identification_number,' . $user->id,
            'hire_date' => 'required|date|before_or_equal:today',
            'area' => 'required|in:Administración,Financiera,Compras,Infraestructura,Operación,Talento Humano,Servicios Varios',
            'status' => 'required|in:Activo',
        ]);

        // Generar el correo electrónico automáticamente
        $emailDomain = $validatedData['country'] === 'Colombia' ? 'global.com.co' : 'global.com.us';
        $baseEmail = strtolower($validatedData['first_name']) . '.' . strtolower($validatedData['last_name']);
        $email = $this->generateUniqueEmail($baseEmail, $emailDomain, $user->id);

        // Agregar el correo electrónico generado a los datos validados
        $validatedData['email'] = $email;

        // Actualizar el usuario en la base de datos
        $user->update($validatedData);

        // Retornar respuesta indicando que el usuario fue actualizado con éxito
        return response()->json(['message' => 'Usuario actualizado con éxito', 'user' => $user], 200);
    }
    

    /**
     * Generar un correo electrónico único.
     *
     * @param  string  $baseEmail
     * @param  string  $domain
     * @return string
     */
    private function generateUniqueEmail($baseEmail, $domain)
    {
        $email = $baseEmail . '@' . $domain;
        $counter = 1;

        // Verificar si el correo ya existe y generar un nuevo correo si es necesario
        while (User::where('email', $email)->exists()) {
            $email = $baseEmail . '.' . $counter . '@' . $domain;
            $counter++;
        }

        return $email;
    }

    /**
     * Eliminar un usuario específico.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Eliminar el usuario de la base de datos
        $user->delete();

        // Retornar respuesta indicando que el usuario fue eliminado con éxito
        return response()->json(['message' => 'Usuario eliminado con éxito'], 200);
    }

    /**
     * Mostrar un usuario específico.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Buscar el usuario por ID
        $user = User::find($id);

        // Verificar si el usuario existe
        if (!$user) {
            // Si no existe, retornar un mensaje personalizado
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Si el usuario existe, retornar los datos del usuario
        return response()->json($user);
    }


    /**
     * Mostrar la lista de usuarios.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return response()->json($users);
    }
   
}
