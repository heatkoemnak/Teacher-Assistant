<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'date_of_birth', 'phone_number', 'address'
    ];

    protected $casts = [
        'address' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
