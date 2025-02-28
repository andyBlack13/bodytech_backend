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

        $activities = Activity::with('user')->get();

        return response()->json([
            'message' => 'Actividades encontradas exitosamente',
            'activities' => $activities
        ], 201); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'action' => 'required|string'
        ]);

        $activity = Activity::create($request->all());

        return response()->json(['message' => 'Actividad registrada', 'activity' => $activity], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        return response()->json($activity);
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
        $activity->delete();
        return response()->json(['message' => 'Actividad eliminada']);
    }

    //GET Actividades por usuario
    public function stats()
    {
        $stats = Activity::selectRaw('user_id, COUNT(*) as total')
            ->groupBy('user_id')
            ->with('user')
            ->get();

        return response()->json($stats);
    }

}
