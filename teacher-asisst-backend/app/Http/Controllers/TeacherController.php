<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Profile;
class TeacherController extends Controller
{
    public function index(){
        $teacher = Teacher::with('user')->get();
        return response()->json($teacher, 200);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'department_id' =>'required|integer|exists:departments,id'
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
                'role_id'=>3
            ]);

            $teacher = Teacher::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'user_id' => $user->id,
                'dob' => $request->dob,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'department_id'=>$request->department_id
            ]);
            $profile = Profile::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'user_id' => $user->id,
                'dob' => $request->dob,
                'phone' => $request->phone,
                'gender' => $request->gender,
            ]);
            return response()->json([$user,$teacher,$profile,'message'=>'register success',], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Registration failed', 'message' => $e->getMessage()], 500);
        }
    }
    public function showTeacher($id)
    {
        try {
            $teacher = Teacher::with('user')->findOrFail($id);
            return response()->json($teacher, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Teacher not found', 'message' => $e->getMessage()], 404);
        }
    }
    public function updateTeacher(Request $request, $id)
    {
        // $validator = Validator::make($request->all(), [
        //     'first_name' => 'sometimes|required|string|max:255',
        //     'last_name' => 'sometimes|required|string|max:255',
        //     'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
        //     'phone' => 'sometimes|required|string|max:15',
        //     'address' => 'sometimes|required|string|max:255',
        //     'dob' => 'sometimes|required|date',
        //     'gender' => 'sometimes|required|string|max:10',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json($validator->errors(), 422);
        // }

        try {
            // Find the teacher and associated user
            $teacher = Teacher::findOrFail($id);
            $user = User::findOrFail($teacher->user_id);

            // Update the user
            if ($request->has('first_name') || $request->has('last_name')) {
                $fullName = strtolower($request->first_name . '' . $request->last_name);
                $user->name = $fullName;
            }
            if ($request->has('email')) {
                $user->email = $request->email;
            }
            $user->save();

            // Update the teacher
            $teacher->update($request->only([
                'first_name',
                'last_name',
                'email',
                'dob',
                'phone',
                'gender',
                'department_id'
            ]));


            return response()->json(['user' => $user, 'teacher' => $teacher], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Update failed', 'message' => $e->getMessage()], 500);
        }
    }
    public function deleteTeacher($id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            $user = User::findOrFail($teacher->user_id);
            $teacher->delete();
            $user->delete();

            return response()->json(['message' => 'Teacher and associated user deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Deletion failed', 'message' => $e->getMessage()], 500);
        }
    }

}
