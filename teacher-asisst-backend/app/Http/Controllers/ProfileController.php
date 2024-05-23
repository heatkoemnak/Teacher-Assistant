<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_profile = Profile::with('user')->get();
        return response()->json( $user_profile
       , 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_profile = Profile::create($request->all());
        
        return response()->json([
            'status' => 'success',
            'data' => $user_profile
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user_profile = Profile::with('user')->findOrFail($id);
        if (!$user_profile) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }
        return response()->json($user_profile
       , 200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user_profile = Profile::findOrFail($id);
        // if (!$user_profile) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'User not found'
        //     ], 404);
        // }
        $user_profile->update($request->all());
        return response()->json(
            $user_profile
      , 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user_profile = Profile::findOrFail($id);
        if (!$user_profile) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        $user_profile->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully'
        ], 204);
    }
}
