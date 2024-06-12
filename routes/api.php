<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\MedicalRecordsController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/create-doctor', [AuthController::class, 'createDoctor']); // New endpoint for creating a doctor account


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/doctors', [DoctorsController::class, 'getAllDoctors']); // View all doctors
    Route::get('/doctors/{id}', [DoctorsController::class, 'getDoctor']); // View doctor details
    Route::put('/doctors/{id}', [DoctorsController::class, 'updateDoctor']); // Update doctor details
    Route::delete('/doctors/{id}', [DoctorsController::class, 'deleteDoctor']); // Delete doctor

    Route::get('/patients', [PatientsController::class, 'getAllPatients']); // View all patients
    Route::get('/patients/{id}', [PatientsController::class, 'getPatient']); // View patient details
    Route::put('/patients/{id}', [PatientsController::class, 'updatePatient']); // Update patient details
    Route::delete('/patients/{id}', [PatientsController::class, 'deletePatient']); // Delete patient

    Route::get('/appointments', [AppointmentsController::class, 'getAllAppointments']);
    Route::get('/appointments/{id}', [AppointmentsController::class, 'getAppointment']);
    Route::post('/appointments', [AppointmentsController::class, 'createAppointment']);
    Route::put('/appointments/{id}', [AppointmentsController::class, 'updateAppointment']);
    Route::delete('/appointments/{id}', [AppointmentsController::class, 'deleteAppointment']);
    Route::put('/appointments/{id}/status', [AppointmentsController::class, 'updateAppointmentStatus']);

    Route::get('/medical-records', [MedicalRecordsController::class, 'getAllMedicalRecords']); // View all medical records
    Route::get('/medical-records/{id}', [MedicalRecordsController::class, 'getMedicalRecord']); // View a single medical record
    Route::post('/medical-records', [MedicalRecordsController::class, 'createMedicalRecord']); // Create a new medical record
    Route::put('/medical-records/{id}', [MedicalRecordsController::class, 'updateMedicalRecord']); // Update a medical record
    Route::delete('/medical-records/{id}', [MedicalRecordsController::class, 'deleteMedicalRecord']); // Delete a medical record
});



