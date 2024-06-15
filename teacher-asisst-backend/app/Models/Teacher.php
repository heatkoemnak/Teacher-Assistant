<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Department;

class Teacher extends Model
{
    use HasFactory;


    protected $fillable = [
        'first_name',
        'last_name',
        'user_id',
        'phone',
        'dob',
        'gender',
        'department_id',
    ];

    protected $casts = [
        'user_id' => 'integer',

    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function classes()
    {
        return $this->belongsToMany(Classes::class);
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }






}
