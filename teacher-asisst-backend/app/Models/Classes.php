<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Student;


class Classes extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class,'class_subject');
    }
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    public function student()
    {
        return $this->hasMany(Student::class,'class_id');
    }
}
