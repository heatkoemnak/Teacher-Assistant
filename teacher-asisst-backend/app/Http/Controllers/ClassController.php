<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ClassModel;
use App\Models\StudentModel;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassModel::with('teacher','students.user.profile')->get();
         return response()->json($classes, 200);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {

            $class = ClassModel::create([
                'name' => $request->name,
                'slug' => $request->slug,
                'image' => $request->image,
                'desc' => $request->desc,
                'teacher_id' => $request->teacher_id,
            ]);

            return response()->json([$class,'message'=>'class created successfully',], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Creation failed', 'message' => $e->getMessage()], 500);
        }
    }
    public function show($id)
    {
        try {
            $class = ClassModel::with('teacher','student')->findOrFail($id);
            return response()->json($class, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Class not found', 'message' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'teacher_id' => 'sometimes|required|exists:teachers,id',
        ]);

        try {
            $class = ClassModel::findOrFail($id);
            $class->update($request->all());

            return response()->json([$class, 'message' => 'Class updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Update failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $class = ClassModel::findOrFail($id);
            $class->delete();

            return response()->json(['message' => 'Class deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Deletion failed', 'message' => $e->getMessage()], 500);
        }
    }
    public function addStudents(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'student_ids' => 'required|array',
        'student_ids.*' => 'exists:student_models,id',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    try {
        $class = ClassModel::findOrFail($id);
        $class->students()->attach($request->student_ids);

        return response()->json(['message' => 'Students added successfully'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to add students', 'message' => $e->getMessage()], 500);
    }
}

}

