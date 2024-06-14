<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController1 extends Controller
{
    public function dashboard()
    {
        // Admin dashboard logic
        return response()->json(['message' => 'Welcome to Admin Dashboard']);
    }
}
