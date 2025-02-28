<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Activity;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$request->headers->set('Accept', 'application/json');
        try {
            $activities = Activity::with('user')->paginate(15);
            
            if ($activities->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron actividades'
                ], 404);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Actividades encontradas exitosamente',
                'activities' => $activities
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las actividades',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'action' => 'required|string'
            ]);
    
            $activity = Activity::create($request->all());
    
            return response()->json([
                'success' => true,
                'message' => 'Actividad registrada',
                'data' => $activity
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar la actividad',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Actividad encontrada exitosamente',
                'data' => $activity
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la actividad',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        try {
            $activity->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Actividad eliminada correctamente'
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la actividad',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    //GET Actividades por usuario
    public function stats()
    {
        try {
            $stats = Activity::selectRaw('user_id, COUNT(*) as total')
                ->groupBy('user_id')
                ->with('user')
                ->paginate(15);
    
            if ($stats->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron datos para generar una estadística'
                ], 404);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Estadísticas generadas exitosamente',
                'data' => $stats
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
