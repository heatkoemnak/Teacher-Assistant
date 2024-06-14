<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index() {
        // Department logic
        $departments = Department::with('subjects')->get();
        return response()->json($departments,200);
    }
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validatedData = $request->validate([
                'name' => 'required|string|max:255', // Adjust the validation rules as needed
            ]);

            // Create the department
            $department = Department::create($validatedData);

            // Return the created department data with a success message
            return response()->json(['message' => 'Department created successfully.', 'department' => $department], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the department.', 'message' => $e->getMessage()], 500);
        }
    }
    public function show($id)
    {
        try {
            // Find the department or throw a 404 error if not found
            $department = Department::findOrFail($id);

            // Return the department data
            return response()->json($department, 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Department not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while retrieving the department.', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate the request
            $request->validate([
                'name' => 'required|string|max:255', // Adjust the validation rules as needed
            ]);

            // Find the department or throw a 404 error if not found
            $department = Department::findOrFail($id);

            // Update the department with validated data
            $department->update($request->only('name'));

            // Return the updated department
            return response()->json($department, 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Department not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the department.', 'message' => $e->getMessage()], 500);
        }
    }
    public function destroy($id)
    {
        try {
            // Find the department or throw a 404 error if not found
            $department = Department::findOrFail($id);

            // Delete the department
            $department->delete();

            // Return a success response
            return response()->json(['message' => 'Department deleted successfully.'], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Department not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the department.', 'message' => $e->getMessage()], 500);
        }
    }

}
