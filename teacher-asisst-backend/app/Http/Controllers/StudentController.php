<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassModel;
use App\Models\StudentModel;
use App\Models\User;
use App\Models\Role;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index(){
        $students = StudentModel::with('user.profile',"classes")->get();
        return response()->json($students, 200);
    }

    public function show($id){
        $student = StudentModel::with('user.profile')->find($id);
        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }
        return response()->json($student, 200);
    }
    public function showStudentViaClass(ClassModel $class){

        $class = ClassModel::FindOrFail($class->id);
        // $student = StudentModel::with('user.profile','classes')->where('user_id', $class->students)->first();
        // if (!$student) {
        //     return response()->json(['error' => 'Student not found'], 404);
        // }
        return response()->json($class, 200);
    }

    public function store(Request $request)
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
            $role = Role::firstOrCreate(['name' => 'student']);

            $full_name = ucfirst(strtolower($request->first_name)) . ' ' . ucfirst(strtolower($request->last_name));
            $user = User::create([
                'name' => $full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $role->id
            ]);

            $student = StudentModel::create([
                'user_id' => $user->id,
            ]);

            $profile = Profile::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'photo' => $request->photo,
                'user_id' => $user->id,
                'dob' => $request->dob,
                'phone' => $request->phone,
                'gender' => $request->gender,
            ]);
            if ($user && $student) {
                $student->user;
                $student->user->profile;
                return response()->json([$student,'message' => 'Student created successfully.'], 201);
            }
            return response()->json([$student, 'message' => 'Student added successfully.'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Registration failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $student = StudentModel::with('user.profile')->find($id);
            if (!$student) {
                return response()->json(['error' => 'Student not found'], 404);
            }

            $user = $student->user;
            if ($request->has('first_name')) {
                $user->profile->first_name = $request->first_name;
                $user->name = ucfirst(strtolower($request->first_name)) . ' ' . ucfirst(strtolower($request->last_name ?? $user->profile->last_name));
            }
            if ($request->has('last_name')) {
                $user->profile->last_name = $request->last_name;
                $user->name = ucfirst(strtolower($request->first_name ?? $user->profile->first_name)) . ' ' . ucfirst(strtolower($request->last_name));
            }
            if ($request->has('email')) {
                $user->email = $request->email;
            }
            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }
            if ($request->has('dob')) {
                $user->profile->dob = $request->dob;
            }
            if ($request->has('phone')) {
                $user->profile->phone = $request->phone;
            }
            if ($request->has('gender')) {
                $user->profile->gender = $request->gender;
            }
            if ($request->has('photo')) {
                $user->profile->photo = $request->photo;
            }

            $user->save();
            $user->profile->save();
            $student->save();

            return response()->json([$user->load('profile'), 'message' => 'Student updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Update failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $student = StudentModel::find($id);
            if (!$student) {
                return response()->json(['error' => 'Student not found'], 404);
            }

            $user = User::find($student->user_id);
            $profile = Profile::where('user_id', $student->user_id)->first();

            if ($profile) {
                $profile->delete();
            }
            if ($user) {
                $user->delete();
            }

            $student->delete();
            return response()->json(['message' => 'Student deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Deletion failed', 'message' => $e->getMessage()], 500);
        }
    }
}
