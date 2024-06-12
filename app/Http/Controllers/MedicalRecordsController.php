<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class MedicalRecordsController extends Controller
{
    public function getAllMedicalRecords()
    {
        $medicalRecords = MedicalRecord::with(['doctor', 'patient'])->get();
        return response()->json($medicalRecords, 200);
    }
    public function getMedicalRecord($id)
    {
        $medicalRecord = MedicalRecord::with(['doctor', 'patient'])->find($id);
        if (!$medicalRecord) {
            return response()->json(['error' => 'Medical Record not found'], 404);
        }
        return response()->json($medicalRecord, 200);
    }

    public function createMedicalRecord(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,user_id',
            'patient_id' => 'required|exists:patients,user_id',
            'record_date' => 'required|date_format:Y-m-d H:i:s',
            'description' => 'required|string',
        ]);

        // Create the Medical Record with the validated data
        $medicalRecord = MedicalRecord::create([
            'doctor_id' => $request->doctor_id,
            'patient_id' => $request->patient_id,
            'record_date' => $request->record_date,
            'description' => $request->description,
        ]);

        return response()->json($medicalRecord, 201);
    }

    public function updateMedicalRecord(Request $request, $id)
    {
        $medicalRecord = MedicalRecord::find($id);
        if (!$medicalRecord) {
            return response()->json(['error' => 'Medical Record not found'], 404);
        }

        $request->validate([
            'doctor_id' => 'required|exists:doctors,user_id',
            'patient_id' => 'required|exists:patients,user_id',
            'record_date' => 'required|date_format:Y-m-d H:i:s',
            'description' => 'required|string',
        ]);

        $medicalRecord->update($request->all());

        return response()->json($medicalRecord, 200);
    }

    public function deleteMedicalRecord($id)
    {
        $medicalRecord = MedicalRecord::find($id);
        if (!$medicalRecord) {
            return response()->json(['error' => 'Medical Record not found'], 404);
        }

        $medicalRecord->delete();
        return response()->json(['message' => 'Medical Record deleted successfully'], 200);
    }






}
