<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class StudentModel extends Model
{
    use HasFactory;
    protected $guarded =[];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classes()
    {
        return $this->belongsToMany(ClassModel::class, 'class_student');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function attendancePercentage()
    {
        $totalDays = $this->attendances()->count();
        $presentDays = $this->attendances()->where('present', true)->count();

        return $totalDays > 0 ? ($presentDays / $totalDays) * 100 : 0;
    }
}

