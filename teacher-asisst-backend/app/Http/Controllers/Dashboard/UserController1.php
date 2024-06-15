<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController1 extends Controller
{
    public function dashboard()
    {
        // Student dashboard logic
        return response()->json(['message' => 'Welcome to User Dashboard']);
    }
}
