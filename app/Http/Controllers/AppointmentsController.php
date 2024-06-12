<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentsController extends Controller
{
    public function getAllAppointments()
    {
        $appointments = Appointment::with(['doctor', 'patient'])->get();
        return response()->json($appointments, 200);
    }

    public function getAppointment($id)
    {
        $appointment = Appointment::with(['doctor', 'patient'])->find($id);
        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found'], 404);
        }
        return response()->json($appointment, 200);
    }

    public function createAppointment(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,user_id',
            'patient_id' => 'required|exists:patients,user_id',
            'appointment_date' => 'required|date_format:Y-m-d H:i:s',
            'status' => 'required|in:booked,approved,completed,canceled',
        ]);

        // Create the appointment with the validated data
        $appointment = Appointment::create([
            'doctor_id' => $request->doctor_id,
            'patient_id' => $request->patient_id,
            'appointment_date' => $request->appointment_date,
            'status' => $request->status,
        ]);

        return response()->json($appointment, 201);
    }

    

    public function updateAppointment(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found'], 404);
        }

        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date_format:Y-m-d H:i:s',
            'status' => 'required|in:booked,approved,completed,canceled',
        ]);

        $appointment->update($request->all());

        return response()->json($appointment, 200);
    }

    public function deleteAppointment($id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found'], 404);
        }

        $appointment->delete();
        return response()->json(['message' => 'Appointment deleted successfully'], 200);
    }

        // New method to update appointment status
        public function updateAppointmentStatus(Request $request, $id)
        {
            $appointment = Appointment::find($id);
            if (!$appointment) {
                return response()->json(['error' => 'Appointment not found'], 404);
            }
    
            $request->validate([
                'status' => 'required|in:booked,approved,completed,canceled',
            ]);
    
            $appointment->status = $request->status;
            $appointment->save();
    
            return response()->json($appointment, 200);
        }
}
