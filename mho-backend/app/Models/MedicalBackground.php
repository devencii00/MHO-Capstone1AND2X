<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalBackground extends Model
{
    use HasFactory;

    protected $table = 'medical_backgrounds';

    protected $primaryKey = 'medical_background_id';

    protected $fillable = [
        'patient_id',
        'category',
        'name',
        'notes',
        'diagnosis_date',
        'diagnosis_time',
        'procedure_date',
    ];

    protected $casts = [
        'diagnosis_date' => 'date',
        'diagnosis_time' => 'datetime:H:i:s',
        'procedure_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id', 'user_id');
    }
}
