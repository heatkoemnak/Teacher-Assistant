<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Classes;

class Student extends Model
{
    use HasFactory;
    protected 
    $fillable = [
        'first_name',
        'last_name',
        'phone',
        'dob',
        'gender',
        'class_id'
    ];
    public function class()
    {
        return $this->belongsTo(Classes::class,);
    }
}
