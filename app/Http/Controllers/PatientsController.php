<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientsController extends Controller
{
    
    public function getAllPatients()
    {
        $patients = Patient::with('user')->get();
        return response()->json($patients, 200);
    }

    public function getPatient($id)
    {
        $patient = Patient::with('user')->find($id);
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }
        return response()->json($patient, 200);
    }
    public function updatePatient(Request $request, $id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $patient->user_id,
            'address' => 'required|string',
            'phone' => 'required|string',
            'birthdate' => 'required|date',
        ]);

        $patient->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
            'birthdate' => $request->birthdate,
        ]);

        return response()->json($patient, 200);
    }

    public function deletePatient($id) 
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        $patient->delete();
        return response()->json(['message' => 'Patient deleted successfully'], 200);
    }

}
