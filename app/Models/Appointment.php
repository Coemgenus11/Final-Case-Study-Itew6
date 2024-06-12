<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'appointment_date',
        'status',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'user_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'user_id');
    }
}
