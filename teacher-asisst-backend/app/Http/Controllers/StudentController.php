<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index()
    {
        return Student::with('class')->get();
    }
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        try {

            $student = Student::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'class_id' => $request->class_id,
                'dob' => $request->dob,
                'phone' => $request->phone,
                'gender' => $request->gender,
            ]);
          
            return response()->json([$student,'message'=>'student added successfully',], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Registration failed', 'message' => $e->getMessage()], 500);
        }
    }

}
