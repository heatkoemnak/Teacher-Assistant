<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Department;

class Teacher extends Model
{
    use HasFactory;
    protected $guarded =[];

    protected $casts = [
        'user_id' => 'integer',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }

}
