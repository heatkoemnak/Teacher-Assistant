<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['class_model_id', 'student_model_id', 'date', 'status'];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_model_id');
    }

    public function student()
    {
        return $this->belongsTo(StudentModel::class, 'student_model_id');
    }
}
