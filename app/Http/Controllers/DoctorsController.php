<?php

namespace App\Http\Controllers;
use App\Models\Doctor;
//use App\Models\User;
use Illuminate\Http\Request;

class DoctorsController extends Controller
{
   
    public function getAllDoctors()
    {
        $doctors = Doctor::with('user')->get();
        return response()->json($doctors, 200);
    }

    public function getDoctor($id)
    {
        $doctor = Doctor::with('user')->find($id);
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }
        return response()->json($doctor, 200);
    }

    public function updateDoctor(Request $request, $id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $doctor->user_id,
            'address' => 'required|string',
            'phone' => 'required|string',
            'birthdate' => 'required|date',
        ]);

        $doctor->update([
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
            'birthdate' => $request->birthdate,
        ]);

        return response()->json($doctor, 200);
    }
    
    public function deleteDoctor($id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }

        $doctor->delete();
        return response()->json(['message' => 'Doctor deleted successfully'], 200);
    }


}
