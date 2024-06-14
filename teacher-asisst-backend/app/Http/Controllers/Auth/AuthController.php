<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
class AuthController extends Controller
{
    public function adminRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $fullName = strtolower($request->first_name . '' . $request->last_name);
            $user = User::create([
                'name' => $fullName,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id'=>1
            ]);
            $profile = Profile::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'user_id' => $user->id,
                'dob' => $request->dob,
                'phone' => $request->phone,
                'gender' => $request->gender,
            ]);
            return response()->json(['user' => $user, 'profile' => $profile,'message'=>'register success'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the user.', 'message' => $e->getMessage()], 500);
        }
    }
    // public function userRegister(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:8',
    //     ]);

    //     try {
    //         $user = User::create([
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'password' => Hash::make($request->password),
    //             'role_id' => 2
    //         ]);

    //         return response()->json(['message' => 'User registered successfully'],201);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'An error occurred while creating the user.', 'message' => $e->getMessage()], 500);
    //     }
    // }


    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json(['token' => $token, 'user' => $user]);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request)
    {
        if ($request->user() === null) {
            // If the user is not authenticated, return a JSON response with an error message
            return response()->json(['message' => 'You are not logged in'], 401);
        }
        return response()->json($request->user());
    }
}
