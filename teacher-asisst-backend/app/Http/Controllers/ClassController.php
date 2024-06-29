<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Subject;
use Illuminate\Support\Facades\Validator;

class ClassController extends Controller
{
    public function index()
    {
        $classes = Classes::with('student','teacher')->get();
         return response()->json($classes, 200);
    }

    public function classWithSubject(){
            $class = Classes::with('subjects')->get();
            return response()->json($class, 200);
    }
    public function createClass(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'teacher_id' => 'required|integer|exists:teacher,id',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        try {

            $class = Classes::create([
                'name' => $request->name,
                'class_id' => $request->teacher_id,
                
            ]);
          
            return response()->json([$class,'message'=>'class created successfully',], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Creation failed', 'message' => $e->getMessage()], 500);
        }
    }



    public function attachSubject(Request $request, $id){

        $class = Classes::findOrFail($id);
        $subject = Subject::findOrFail($request->subject_id);

        $class->subjects()->syncWithoutDetaching($subject);

        return response()->json(['message' => 'Subject attached to class successfully',$class]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'teacher_id' => 'required|exists:teachers,id',
            'subjects' => 'array|exists:subjects,id',
        ]);

        $class = Classes::create($request->only('name' ));
        $class->subjects()->attach($request->subjects);
        if(!$class){
            return response()->json('error', 'Something went wrong.');
        }
        return response()->json($classes, 200);

    }

    public function show($id)
    {
        $class = Classes::with('subjects')->findOrFail($id);
        return $class;
    }

    public function edit($id)
    {
        $class = Classes::with('subjects')->findOrFail($id);
        $subjects = Subject::all();
        return view('classes.edit', compact('class', 'subjects'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'teacher_id' => 'required|exists:teachers,id',
            'subjects' => 'array|exists:subjects,id',
        ]);

        $class = Classes::findOrFail($id);
        $class->update($request->only('name', 'teacher_id'));
        $class->subjects()->sync($request->subjects);

        return redirect()->route('classes.index')->with('success', 'Class updated successfully.');
    }

    public function destroy($id)
    {
        $class = Classes::findOrFail($id);
        $class->subjects()->detach();
        $class->delete();

        return redirect()->route('classes.index')->with('success', 'Class deleted successfully.');
    }
}

