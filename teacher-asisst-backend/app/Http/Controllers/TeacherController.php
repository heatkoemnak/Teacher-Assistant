<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Profile;
use App\Models\Role;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('user.profile','classes.students.user.profile')->get();
        return response()->json($teachers, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'dob' => 'nullable|date',
            'phone' => 'nullable|string|max:15',
            'gender' => 'nullable|string|in:male,female,other',
            'photo' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $role = Role::firstOrCreate(['name' => 'teacher']);

            $full_name = ucfirst(strtolower($request->first_name)) . ' ' . ucfirst(strtolower($request->last_name));
            $user = User::create([
                'name' => $full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $role->id
            ]);

            $teacher = Teacher::create([
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

            if ($user && $teacher) {
                $teacher->user;
                $teacher->user->profile;
                return response()->json([$teacher,'message' => 'Teacher created successfully.'], 201);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Registration failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $teacher = Teacher::with('user.profile', 'classes')->find($id);

        if (!$teacher) {
            return response()->json(['error' => 'Teacher not found'], 404);
        }

        return response()->json($teacher, 200);
    }
    public function showTeacherWithUserID(User $user)
{
    // Get the user with the given ID
    $user = User::findOrFail($user->id);

    // Get the teacher associated with this user
    $teacher = Teacher::with('user.profile', 'classes')->where('user_id', $user->id)->first();

    // Check if the teacher exists
    if (!$teacher) {
        return response()->json(['error' => 'Teacher not found'], 404);
    }

    // Return the user and teacher details
    return response()->json([
        'user' => $user,
        'teacher' => $teacher
    ], 200);
}


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|nullable|string|min:8|confirmed',
            'dob' => 'nullable|date',
            'phone' => 'nullable|string|max:15',
            'gender' => 'nullable|string|in:male,female,other',
            'photo' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $teacher = Teacher::find($id);

            if (!$teacher) {
                return response()->json(['error' => 'Teacher not found'], 404);
            }

            $user = User::find($teacher->user_id);
            $profile = Profile::where('user_id', $user->id)->first();

            $teacher->update([
                'user_id' => $user->id,
            ]);

            $user->name = $request->get('first_name', $user->name) . ' ' . $request->get('last_name', '');
            $user->email = $request->get('email', $user->email);
            if ($request->has('password') && !empty($request->password)) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $profile->update([
                'first_name' => $request->get('first_name', $profile->first_name),
                'last_name' => $request->get('last_name', $profile->last_name),
                'photo' => $request->get('photo', $profile->photo),
                'dob' => $request->get('dob', $profile->dob),
                'phone' => $request->get('phone', $profile->phone),
                'gender' => $request->get('gender', $profile->gender),
            ]);

            return response()->json([$teacher, 'message' => 'Teacher updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Update failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $teacher = Teacher::find($id);

            if (!$teacher) {
                return response()->json(['error' => 'Teacher not found'], 404);
            }

            $user = User::find($teacher->user_id);
            $profile = Profile::where('user_id', $user->id)->first();

            if ($user) {
                $user->delete();
            }

            if ($profile) {
                $profile->delete();
            }

            $teacher->delete();

            return response()->json(['message' => 'Teacher deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Deletion failed', 'message' => $e->getMessage()], 500);
        }
    }
}
