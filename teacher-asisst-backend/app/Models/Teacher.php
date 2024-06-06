<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Teacher extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'user_id',
        'phone',
        'address',
        'dob',
        'gender',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
