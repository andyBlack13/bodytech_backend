<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $request->headers->set('Accept', 'application/json');

            $query = User::query();

            if ($request->has('name')) {
                $query->where('name', 'LIKE', '%' . $request->name . '%');
            }

            if ($request->has('email')) {
                $query->where('email', $request->email);
            }

            if ($request->has('phone')) {
                $query->where('phone', $request->phone);
            }

            // por fecha de creación -->rango (mejorar)
            // api/users?created_at_from=2025-02-28&created_at_to=2025-03-02

            if ($request->has('created_at_from')) {
                $query->whereDate('created_at', '>=', $request->created_at_from);
            }

            if ($request->has('created_at_to')) {
                $query->whereDate('created_at', '<=', $request->created_at_to);
            }
            
            $users = $query->with(['activities'])->paginate(20);

            return response()->json([
                'success' => true,
                'message' => 'Usuarios encontrados exitosamente',
                'data' => $users
            ], 200); 

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener usuarios',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Utilizar el endpoint de register para registrar usuarios'
        ], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Usuario encontrado exitosamente',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
                'phone' => 'nullable|string',
                'password' => 'nullable|string|min:6'
            ]);

            $data = $request->only(['name', 'email', 'phone', 'password']);

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            } else {
                unset($data['password']);
            }

            $user->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado',
                'data' => $user
            ], 200); 

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
