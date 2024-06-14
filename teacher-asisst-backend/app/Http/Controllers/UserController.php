<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
class UserController extends Controller
{
    public function index(){
        $users = User::with('role','profile')->get();
        return response()->json($users, 200);
    }
    public function getUsersWithRoleId($id)
    {
        try {
            $users = User::where('role_id', $id)->with('role')->get();
            if ($users->isEmpty()) {
                return response()->json(['error' => 'No users found with the specified role ID.'], 404);
            }
            return response()->json($users, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while retrieving users.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|integer|exists:roles,id',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
            ]);

            return response()->json($user, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the user.', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::with('profile')->findOrFail($id);
            $user->role_name = $user->role->name; // Assuming there is a role relationship defined in the User model
            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'User not found', 'message' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $id,
            'password' => 'string|min:8|nullable',
            'role_id' => 'integer|exists:roles,id',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->name = $request->name ?? $user->name;
            $user->email = $request->email ?? $user->email;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->role_id = $request->role_id ?? $user->role_id;
            $user->save();

            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the user.', 'message' => $e->getMessage()], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the user.', 'message' => $e->getMessage()], 500);
        }
    }


}
