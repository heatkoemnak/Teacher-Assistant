<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
class RoleController extends Controller
{
    public function index(){

        $roles = Role::with('users',"permissions")->get();
        // foreach ($roles as $role) {
        //     $users= $role->users; // Access users for each model
        //     foreach ($users as $user) {
        //         $roles->users= $user->name; // Access users for each model
        //     }
        // }
        return $roles;
    }
    public function show($id){
        $role = Role::with('users')->findOrFail($id);
        //  foreach (User::all() as $user) {
        // }
        // $user = $role->users;
        // $role->users = $user->name;
        return $role;
    }
    public function store(Request $request)
    {
        $role = Role::create($request->all());
        $role->permissions()->sync($request->permissions);
        return response()->json($role->load('permissions'), 201);
    }
    public function destroy($id)
    {
        Role::find($id)->delete();
        return response()->json(null, 204);
    }
}
