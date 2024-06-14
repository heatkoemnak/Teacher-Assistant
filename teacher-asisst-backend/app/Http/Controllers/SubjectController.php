<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Classes;


class SubjectController extends Controller
{
    public function index()
    {
        try {
            $subjects = Subject::all();
            return response()->json($subjects, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching subjects.', 'message' => $e->getMessage()], 500);
        }
    }

    public function subjectWithClasses(){
        try {
            $subjects = Subject::with('classes')->get();
            return response()->json($subjects, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching subjects with classes.', 'message' => $e->getMessage()], 500);
        }
    }
    public function attachClassToSubject(Request $request, $id){

        $subject = Subject::findOrFail($id);
        $class = CLasses::findOrFail($request->classes_id);

        $subject->classes()->syncWithoutDetaching($class);

        return response()->json(['message' => 'Classes attached to subject successfully',$subject]);
    }
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validatedData = $request->validate([
                'name' => 'required|string|max:255', // Adjust the validation rules as needed
                'department_id' =>'required|integer|exists:departments,id', // Adjust the validation rules as needed
            ]);


            // Create the department
            $subject  = Subject::create($validatedData);

            if($subject->has('classes')){
                $subject->classes()->attach($request->classes);
            }

            // Return the created department data with a success message
            return response()->json(['message' => 'Subject created successfully.'], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the department.', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $subject = Subject::findOrFail($id);
            return response()->json($subject, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'subject not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching the subject.', 'message' => $e->getMessage()], 500);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $subject = Subject::find($id);
            if (!$subject) {
                return response()->json(['error' => 'Subject not found'], 404);
            }
            $subject->update($request->only('name','department_id'));
            return response()->json(['message' => 'Subject updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating subject.', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Find the department or throw a 404 error if not found
            $subject = Subject::findOrFail($id);

            // Delete the department
            $subject->delete();
            $subject->classes->detach();
            // Return a success response
            return response()->json(['message' => 'Subject deleted successfully.'], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Subject not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the subject.', 'message' => $e->getMessage()], 500);
        }
    }

}
