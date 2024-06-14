<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subject;
use App\Models\Teacher;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

}
