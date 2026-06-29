<?php
namespace App\Http\Controllers;

use App\Models\Ambulance;
use Illuminate\Http\Request;

class AmbulanceController extends Controller
{
    public function index()
    {
        $ambulances = Ambulance::with('ambulancier')->get();
        return view('regulateur.gestion-flotte', compact('ambulances'));
    }

    public function updateStatut(Request $request, $id)
    {
        $ambulance = Ambulance::findOrFail($id);
        $ambulance->update(['statut' => $request->statut]);
        return response()->json(['success' => true]);
    }

    public function updatePosition(Request $request, $id)
    {
        $ambulance = Ambulance::findOrFail($id);
        $ambulance->update([
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
        ]);
        return response()->json(['success' => true]);
    }
}