<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function createDoctor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'required|string',
            'phone' => 'required|string',
            'birthdate' => 'required|date',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => 'doctor',
        ]);

        Doctor::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
            'birthdate' => $request->birthdate,
        ]);

        return response()->json(['message' => 'Doctor account created successfully'], 201);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'required|string',
            'phone' => 'required|string',
            'birthdate' => 'required|date',
            'user_type' => 'required|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type, // Default to patient for registration
        ]);

        if ($user->user_type === 'patient') {
            Patient::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'phone' => $request->phone,
                'birthdate' => $request->birthdate,
            ]);
        }
        if ($user->user_type === 'doctor') {
            Doctor::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'phone' => $request->phone,
                'birthdate' => $request->birthdate,
            ]);
        }

        return response()->json(['message' => 'User registered successfully'], 201);
    }



    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
    
        $token = $user->createToken('auth-token')->plainTextToken;
    
        // Fetch the doctor ID if the user is a doctor
        $user_id = null;
        if ($user->user_type === 'doctor') {
            $doctor = $user->doctor; // Assuming a hasOne relationship between User and Doctor
            $user_id = $doctor->id;

            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'user_type' => $user->user_type,
                    'doctor_id' => $user_id,
                ]
            ], 200);
        }

        else if ($user->user_type === 'patient') {
            $patient = $user->patient; // Assuming a hasOne relationship between User and Doctor
            $user_id = $patient->id;

            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'user_type' => $user->user_type,
                    'patient_id' => $user_id,
                ]
            ], 200);
        }

        else if ($user->user_type === 'admin') {
         
            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'user_type' => $user->user_type,
                ]
            ], 200);
        }


    }
    

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully'], 200);
    }
}
