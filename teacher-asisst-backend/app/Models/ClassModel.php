<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;
use App\Models\StudentModel;

class ClassModel extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students()
    {
        return $this->belongsToMany(StudentModel::class, 'class_student');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'class_model_id');
    }
}

