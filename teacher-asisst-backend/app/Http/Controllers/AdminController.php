<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminModel;
use App\Models\User;
use App\Models\Role;
use App\Models\Profile;

class AdminController extends Controller
{
    public function index(){
        $admins = AdminModel::with('user.profile')->get();
        return response()->json($admins, 200);
    }

    public function show($id){
        $admin = AdminModel::with('user.profile')->find($id);
        if (!$admin) {
            return response()->json(['error' => 'Admin not found'], 404);
        }
        return response()->json($admin, 200);
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
            $role = Role::firstOrCreate(['name' => 'admin']);

            $full_name = ucfirst(strtolower($request->first_name)) . ' ' . ucfirst(strtolower($request->last_name));
            $user = User::create([
                'name' => $full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $role->id
            ]);

            $admin = AdminModel::create(['user_id' => $user->id]);

            $profile = Profile::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'photo' => $request->photo,
                'user_id' => $user->id,
                'dob' => $request->dob,
                'phone' => $request->phone,
                'gender' => $request->gender,
            ]);

            if ($user && $admin) {
                $admin->user;
                $admin->user->profile;
                return response()->json([$admin,'message' => 'Admin created successfully.'], 201);
            }
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
            $admin = AdminModel::with('user.profile')->find($id);
            if (!$admin) {
                return response()->json(['error' => 'Admin not found'], 404);
            }

            $user = $admin->user;
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

            return response()->json([$user->load('profile'), 'message' => 'Admin updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Update failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $admin = AdminModel::find($id);
            if (!$admin) {
                return response()->json(['error' => 'Admin not found'], 404);
            }

            $user = User::find($admin->user_id);
            $profile = Profile::where('user_id', $admin->user_id)->first();

            if ($profile) {
                $profile->delete();
            }
            if ($user) {
                $user->delete();
            }

            $admin->delete();
            return response()->json(['message' => 'Admin deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Deletion failed', 'message' => $e->getMessage()], 500);
        }
    }
}
