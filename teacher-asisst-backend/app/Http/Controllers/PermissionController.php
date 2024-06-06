<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Permission;
class PermissionController extends Controller
{
    public function index(){
        $permissions = Permission::get();
        return response()->json($permissions, 200);
    }

    // Get a single permission by ID
    public function show($id){
        try {
        $permission = Permission::findOrFail($id);
        if (!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }
        return response()->json($permission, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Permission not found', 'message' => $e->getMessage()], 404);
        }
    }

    // Create a new permission
    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
        ]);

        $permission = Permission::create([
            'name' => $request->name,
            'role_id'=>$request->role_id
        ]);

        return response()->json($permission, 201);
    }

    // Update an existing permission
    public function update(Request $request, $id){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $permission = Permission::findOrFail($id);

            $permission->update([
                'name' => $request->name,
                'role_id' => $request->role_id,
            ]);

            return response()->json($permission, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Permission not found', 'message' => $e->getMessage()], 404);
        }

    }

    // Delete a permission
    public function destroy($id){
        $permission = Permission::findOrFail($id);
        if (!$permission) {
            return response()->json(['message' => 'Permission not found'], 404);
        }

        $permission->delete();

        return response()->json(['message' => 'Permission deleted successfully'], 200);
    }
}
