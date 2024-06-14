<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeacherController1 extends Controller
{
    public function dashboard()
    {
        // Teacher dashboard logic
        return response()->json(['message' => 'Welcome to Teacher Dashboard']);
    }
}
