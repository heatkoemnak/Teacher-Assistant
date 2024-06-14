<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Department;
use App\Models\Classes;


class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'department_id'];

    public function classes()
    {
        return $this->belongsToMany(Classes::class,'class_subject');
    }
}
